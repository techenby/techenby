import * as THREE from 'three'
import { OrbitControls } from 'three/addons/controls/OrbitControls.js'
import { STLLoader } from 'three/addons/loaders/STLLoader.js'

const prefersReducedMotion = () =>
    document.documentElement.classList.contains('reduce-motion') ||
    window.matchMedia('(prefers-reduced-motion: reduce)').matches

export const mountStlViewer = (viewer) => {
    const canvas = viewer.querySelector('[data-stl-canvas]')
    const status = viewer.querySelector('[data-stl-status]')
    const resetButton = viewer.querySelector('[data-stl-reset]')

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true })
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))

    const scene = new THREE.Scene()
    scene.add(new THREE.HemisphereLight(0xffffff, 0x2c2c2c, 2.5))

    const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 10000)
    const light = new THREE.DirectionalLight(0xffffff, 2)
    camera.add(light)
    light.position.set(1, 1, 2)
    scene.add(camera)

    const controls = new OrbitControls(camera, canvas)
    controls.enableDamping = true
    controls.autoRotate = !prefersReducedMotion()
    controls.autoRotateSpeed = 1.5
    // Small tiles sit in scrolling lists, so leave the wheel and swipes to the page.
    if ('stlCompact' in viewer.dataset) {
        controls.enableZoom = false
        controls.enablePan = false
    }

    controls.addEventListener('start', () => {
        controls.autoRotate = false
        viewer.dataset.dragging = ''
    })
    controls.addEventListener('end', () => {
        delete viewer.dataset.dragging
    })

    const resize = () => {
        const { clientWidth, clientHeight } = canvas

        renderer.setSize(clientWidth, clientHeight, false)
        camera.aspect = clientWidth / clientHeight
        camera.updateProjectionMatrix()
    }

    new ResizeObserver(resize).observe(canvas)
    resize()

    let isVisible = true

    new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting
    }).observe(canvas)

    renderer.setAnimationLoop(() => {
        if (!isVisible) {
            return
        }

        controls.update()
        renderer.render(scene, camera)
    })

    new STLLoader().load(
        viewer.dataset.stlUrl,
        (geometry) => {
            // Slicers treat Z as up; three.js treats Y as up.
            geometry.rotateX(-Math.PI / 2)
            geometry.center()
            geometry.computeVertexNormals()
            geometry.computeBoundingSphere()

            const material = new THREE.MeshStandardMaterial({ color: 0x9c59d1, roughness: 0.55, metalness: 0.05 })
            scene.add(new THREE.Mesh(geometry, material))

            const radius = geometry.boundingSphere.radius
            const distance = radius / Math.sin(THREE.MathUtils.degToRad(camera.fov / 2))

            camera.position.set(distance * 0.6, distance * 0.45, distance * 0.65)
            camera.near = distance / 100
            camera.far = distance * 100
            camera.updateProjectionMatrix()

            controls.minDistance = radius
            controls.maxDistance = distance * 4
            controls.saveState()

            status.remove()
        },
        undefined,
        () => {
            status.textContent = 'The 3D model couldn’t load. You can still download it below.'
        },
    )

    resetButton?.addEventListener('click', () => {
        controls.reset()
    })
}
