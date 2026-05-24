document.querySelectorAll('[data-uses]').forEach((uses) => {
    const views = uses.querySelectorAll('[data-uses-view]')
    const viewButtons = uses.querySelectorAll('[data-uses-view-button]')
    const hotspots = uses.querySelectorAll('[data-uses-item]')
    const defaultView = 'desk'
    const sceneParam = 'scene'

    const hasView = (viewName) => Array.from(views).some((view) => view.dataset.usesView === viewName)

    const sceneFromUrl = () => {
        const requestedScene = new URL(window.location.href).searchParams.get(sceneParam)

        return requestedScene && hasView(requestedScene) ? requestedScene : defaultView
    }

    const updateSceneUrl = (viewName, mode = 'push') => {
        const url = new URL(window.location.href)

        if (viewName === defaultView) {
            url.searchParams.delete(sceneParam)
        } else {
            url.searchParams.set(sceneParam, viewName)
        }

        if (url.href === window.location.href) {
            return
        }

        window.history[`${mode}State`]({}, '', url)
    }

    const parseLinks = (encodedLinks) => {
        if (!encodedLinks) {
            return []
        }

        try {
            return JSON.parse(atob(encodedLinks))
        } catch {
            return []
        }
    }

    const setView = (viewName, options = {}) => {
        const nextView = hasView(viewName) ? viewName : defaultView

        views.forEach((view) => {
            view.hidden = view.dataset.usesView !== nextView
        })

        viewButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.usesViewButton === nextView)
        })

        if (options.history) {
            updateSceneUrl(nextView, options.history)
        }
    }

    const inspectItem = (hotspot) => {
        hotspots.forEach((item) => item.classList.toggle('is-selected', item === hotspot))

        const view = hotspot.closest('[data-uses-view]') || uses
        const panelName = view.querySelector('[data-uses-panel-name]')
        const panelType = view.querySelector('[data-uses-panel-type]')
        const panelDescription = view.querySelector('[data-uses-panel-description]')
        const panelLinks = view.querySelector('[data-uses-panel-links]')

        if (!panelName || !panelType || !panelDescription || !panelLinks) {
            return
        }

        panelName.textContent = hotspot.dataset.usesName
        panelType.textContent = hotspot.dataset.usesType
        panelDescription.textContent = hotspot.dataset.usesDescription

        const links = parseLinks(hotspot.dataset.usesLinks)

        panelLinks.replaceChildren()
        panelLinks.hidden = links.length === 0

        links.forEach((link) => {
            const item = document.createElement('li')
            const anchor = document.createElement('a')

            anchor.href = link.url
            anchor.textContent = link.label
            anchor.target = '_blank'
            anchor.rel = 'noopener'

            item.append(anchor)
            panelLinks.append(item)
        })
    }

    viewButtons.forEach((button) => {
        button.addEventListener('click', () => setView(button.dataset.usesViewButton, { history: 'push' }))
    })

    hotspots.forEach((hotspot) => {
        hotspot.addEventListener('pointerenter', () => inspectItem(hotspot))
        hotspot.addEventListener('focus', () => inspectItem(hotspot))
        hotspot.addEventListener('click', () => {
            inspectItem(hotspot)

            if (hotspot.dataset.usesAction) {
                setView(hotspot.dataset.usesAction, { history: 'push' })
            }
        })
    })

    setView(sceneFromUrl(), { history: 'replace' })

    window.addEventListener('popstate', () => {
        setView(sceneFromUrl())
    })
})
