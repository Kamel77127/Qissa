
let isMenuHidden = true;
let isMennuHidden = true;
let principalTitle = document.getElementById('nav_title_principal');
let menuMb = document.querySelector('.menu__burger');
let leaveMb = document.querySelector('#leave__mb');

menuMb.addEventListener('click', () => {
    let menuMmb = document.querySelector('.menu__mb');
    if (isMenuHidden) {
        menuMmb.style.left = "0"
        principalTitle.style.display = "none"
    }
    else {
        menuMmb.style.left = "-100%"
    }
});

leaveMb.addEventListener('click', () => {
    let menuMmb = document.querySelector('.menu__mb');

    if (isMennuHidden) {
        menuMmb.style.left = "-100%"
        principalTitle.style.display = "flex"
    }
    else {
        menuMmb.style.left = "0"

    }

});


let adminBtn = document.querySelector('.admin__button');
let header = document.querySelector('.head');
let adminMenu = document.querySelector('.admin__menu');





const showMenu = () => {
    header.classList.toggle('grow__header');
    header.classList.remove('sticky');
    adminMenu.classList.toggle('visibility__none');
    adminMenu.classList.toggle('visibility__visible')
}




if(adminBtn)
{
    adminBtn.addEventListener('click' , showMenu)
}

const stickyNav = () => {
    let header = document.querySelector("header");
    header.classList.toggle("sticky", window.scrollY > 0);
}
window.addEventListener("scroll", stickyNav);