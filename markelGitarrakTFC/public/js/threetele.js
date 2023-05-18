import * as THREE from '../js/three.js';
import {
    OBJLoader
} from 'https://cdn.jsdelivr.net/npm/three@0.152.2/examples/jsm/loaders/OBJLoader.js';
import {
    MTLLoader
} from 'https://cdn.jsdelivr.net/npm/three@0.152.2/examples/jsm/loaders/MTLLoader.js';

var camera, scene, renderer, stats, windowHalfX = window.innerWidth / 2,
    windowHalfY = window.innerHeight / 2,
    mouseX = 0,
    mouseY = 0;

init();
animate();

function init() {
    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 1, 2000);
    camera.position.set(30, 30, 30)
    scene = new THREE.Scene();

    scene.add(new THREE.Mesh(
        new THREE.BoxGeometry(5, 5, 5),
        new THREE.MeshBasicMaterial({
            color: 0x00ff00
        })
    ));

    const mtlLoader = new THREE.MTLLoader()
    mtlLoader.load('{{ asset("storage/3d/tele.mtl" ) }}', (materials) => {
        materials.preload()
        // loading geometry
        const objLoader = new THREE.OBJLoader()
        objLoader.setMaterials(materials)
        objLoader.load('{{ asset("storage/3d/tele.obj" ) }}', (object) => {
            mesh = object
            scene.add(mesh)
        })
    })

    renderer = new THREE.WebGLRenderer({
        antialias: true,
        canvas: document.querySelector('canvas'),
        alpha: true
    });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    window.addEventListener('mousemove', onDocumentMouseMove, false);
}

function onDocumentMouseMove(event) {
    mouseX = (event.clientX - windowHalfX) / 10;
    mouseY = (event.clientY - windowHalfY) / 10;
}

function animate() {
    requestAnimationFrame(animate);
    render();
}

function render() {
    camera.position.x += (mouseX - camera.position.x) * .05;
    camera.position.y += (-mouseY - camera.position.y) * .05;
    camera.lookAt(scene.position);
    renderer.render(scene, camera);
}