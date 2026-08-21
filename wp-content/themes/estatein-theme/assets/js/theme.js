// document.addEventListener('DOMContentLoaded',()=>{
//     document.querySelectorAll('.announcement-close').forEach(btn=>btn.addEventListener('click',()=>{
//         const bar=btn.closest('.announcement-bar');if(bar)bar.remove()
//     }));
// });

jQuery(function ($) {
    $('.hamburger').on('click', function () {
        $(this).toggleClass('is-active');
    });
    $('.announcement-close').on('click', function () {
        $(this).closest('.announcement-bar').slideUp();
        console.log('sliding up...')
    });
});
