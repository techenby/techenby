document.querySelectorAll('[data-uses]').forEach((uses) => {
    const views = uses.querySelectorAll('[data-uses-view]')
    const viewButtons = uses.querySelectorAll('[data-uses-view-button]')
    const hotspots = uses.querySelectorAll('[data-uses-item]')
    const panelName = uses.querySelector('[data-uses-panel-name]')
    const panelType = uses.querySelector('[data-uses-panel-type]')
    const panelDescription = uses.querySelector('[data-uses-panel-description]')

    const setView = (viewName) => {
        views.forEach((view) => {
            view.hidden = view.dataset.usesView !== viewName
        })

        viewButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.usesViewButton === viewName)
        })
    }

    const inspectItem = (hotspot) => {
        hotspots.forEach((item) => item.classList.toggle('is-selected', item === hotspot))

        panelName.textContent = hotspot.dataset.usesName
        panelType.textContent = hotspot.dataset.usesType
        panelDescription.textContent = hotspot.dataset.usesDescription
    }

    viewButtons.forEach((button) => {
        button.addEventListener('click', () => setView(button.dataset.usesViewButton))
    })

    hotspots.forEach((hotspot) => {
        hotspot.addEventListener('pointerenter', () => inspectItem(hotspot))
        hotspot.addEventListener('focus', () => inspectItem(hotspot))
        hotspot.addEventListener('click', () => {
            inspectItem(hotspot)

            if (hotspot.dataset.usesAction === 'desktop') {
                setView('desktop')
            }
        })
    })
})
