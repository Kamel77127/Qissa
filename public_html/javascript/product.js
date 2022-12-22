let description = document.querySelector('.description');
let informations = document.querySelector('.informations');
let avis = document.querySelector('.avis');
let hrDesc = document.getElementById('hr1');
let hrInf = document.getElementById('hr2');
let hrAv = document.getElementById('hr3');
let mdlg_first = document.getElementById('md_lg1');
let mdlg_second = document.getElementById('md_lg2');
let mdlg_third = document.getElementById('md_lg3');
let desc = document.querySelector('.desc');
let inf = document.querySelector('.inf');
let av = document.querySelector('.av');
console.log(mdlg_first);

let isDescOpen = true;




if (isDescOpen) {
    description.classList.add('open')
    hrDesc.classList.add('open'),
        mdlg_first.classList.add('open'),

        informations.classList.add('close'),
        mdlg_second.classList.add('close'),
        avis.classList.add('close'),
        mdlg_third.classList.add('close'),
        hrInf.classList.add('close'),
        hrAv.classList.add('close'),

        description.classList.remove('close'),
    hrDesc.classList.remove('close'),
        mdlg_first.classList.remove('close'),

        informations.classList.remove('open'),
        mdlg_second.classList.remove('open'),
        avis.classList.remove('open'),
        mdlg_third.classList.remove('open'),
        hrInf.classList.remove('open'),
        hrAv.classList.remove('open');
}


desc.addEventListener('click', () => {

    description.classList.add('open'),
    hrDesc.classList.add('open'),
        mdlg_first.classList.add('open'),
        informations.classList.add('close'),
        mdlg_second.classList.add('close'),
        avis.classList.add('close'),
        mdlg_third.classList.add('close'),
        hrInf.classList.add('close'),
        hrAv.classList.add('close'),

        description.classList.remove('close'),
    hrDesc.classList.remove('close'),
    mdlg_first.classList.remove('close'),
    description.classList.remove('close'),

        informations.classList.remove('open'),
        mdlg_second.classList.remove('open'),
        avis.classList.remove('open'),
        mdlg_third.classList.remove('open'),
        hrInf.classList.remove('open'),
        hrAv.classList.remove('open');
});






inf.addEventListener('click', () => {
    description.classList.add('close'),

        hrDesc.classList.add('close'),
        mdlg_first.classList.add('close'),
        description.classList.remove('open'),
        hrDesc.classList.remove('open'),
        mdlg_first.classList.remove('open'),


        informations.classList.remove('close'),
        mdlg_second.classList.remove('close'),
        avis.classList.remove('open'),
        mdlg_third.classList.remove('open'),
        hrInf.classList.remove('close'),
        hrAv.classList.remove('open'),

        informations.classList.add('open'),
        mdlg_second.classList.add('open'),
        avis.classList.add('close'),
        mdlg_third.classList.add('close'),
        hrInf.classList.add('open'),
        hrAv.classList.add('close');


});

av.addEventListener('click', () => {
    description.classList.add('close'),
        hrDesc.classList.add('close'),
        mdlg_first.classList.add('close'),

        informations.classList.add('close'),
        mdlg_second.classList.add('close'),
        avis.classList.add('open'),
        mdlg_third.classList.add('open'),
        hrInf.classList.add('close'),
        hrAv.classList.add('open'),

        description.classList.remove('open'),
        hrDesc.classList.remove('open'),
        mdlg_first.classList.remove('open'),

        informations.classList.remove('open'),
        mdlg_second.classList.remove('open'),
        avis.classList.remove('close'),
        mdlg_third.classList.remove('close'),
        hrInf.classList.remove('open'),
        hrAv.classList.remove('close');
});

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