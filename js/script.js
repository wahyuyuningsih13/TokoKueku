// Toggle class active
const navMenu = document.querySelector('.nav-menu');

// Ketika Hamburger di klik
document.querySelector('#hamburger-menu').onclick = () => {
    navMenu.classList.toggle('active');
};

// Klik di luar sidebar
const hamburger = document.querySelector('#hamburger-menu');

document.addEventListener('click', function(e) {
    if(!hamburger.contains(e.target) && !navMenu.contains(e.target)){
        navMenu.classList.remove('active');
    }
});