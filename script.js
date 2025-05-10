const header = document.querySelector('header');
function fixedNavbar(){
    header.classList.toggle('scroll', window.pageYOffset>0)
}
fixedNavbar();
window.addEventListener('scroll', fixedNavbar);

let menu =document.querySelector('#menu-btn');
let userBtn =document.querySelector('#user-btn');

menu.addEventListener('click',function(){
let nav = document.querySelector('.navbar');
nav.classList.toggle('active');
})
userBtn.addEventListener('click',function(){
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active')
})
"use strict";

// تحديد الأسهم والسلايدر
const leftArrow = document.querySelector('.left-arrow .bxs-left-arrow');
const rightArrow = document.querySelector('.right-arrow .bxs-right-arrow');
const slider = document.querySelector('.slider');

// التحقق من وجود العناصر
if (!leftArrow || !rightArrow || !slider) {
    console.error("One or more elements (leftArrow, rightArrow, slider) are missing in the DOM.");
} else {
    // التمرير إلى اليمين
    function scrollRight() {
        if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth) {
            // إذا وصلنا إلى نهاية السلايدر، نعود إلى البداية
            slider.scrollTo({
                left: 0,
                behavior: 'smooth'
            });
        } else {
            // التمرير إلى اليمين
            slider.scrollBy({
                left: slider.clientWidth, // تمرير بعرض السلايدر
                behavior: 'smooth'
            });
        }
    }

    // التمرير إلى اليسار
    function scrollLeft() {
        if (slider.scrollLeft === 0) {
            // إذا كنا في البداية، نذهب إلى النهاية
            slider.scrollTo({
                left: slider.scrollWidth,
                behavior: 'smooth'
            });
        } else {
            // التمرير إلى اليسار
            slider.scrollBy({
                left: -slider.clientWidth,
                behavior: 'smooth'
            });
        }
    }

    // التمرير التلقائي كل 7 ثوانٍ
    let timerid = setInterval(scrollRight, 7000);

    // إعادة ضبط المؤقت
    function resetTimer() {
        clearInterval(timerid);
        timerid = setInterval(scrollRight, 7000);
    }

    // إضافة الأحداث للنقر على الأسهم
    rightArrow.addEventListener('click', function () {
        scrollRight();
        resetTimer();
    });

    leftArrow.addEventListener('click', function () {
        scrollLeft();
        resetTimer();
    });
}
//testimonial slider 
// let slide =decument.querySelectorAll('.testimonial-item');
// let index=0;
// function nextSlide(){
//     slide[index].classList.remove('action');
//     index = (index+1)%slide.length;
//     slide[index].classList.add('active');
// }
// function prevSlide(){
//     let slide =decument.querySelectorAll('.testimonial-item');
//     index=(index-1+slide.length)%slide.length;
//     slide[index].classList.add('active');
// }