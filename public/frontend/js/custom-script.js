$(document).ready(function () {


    // $('.select2-select').select2();
    // $(document).on('select2:open', '.select2', function (e) {
    //     setTimeout(() => {
    //         const $elem = $(this).attr("id");
    //         const $placeholder = $(this).attr("data-placeholder");
    //         document.querySelector(`[aria-controls="select2-${$elem}-results"]`).focus();
    //         document.querySelector(`[aria-controls="select2-${$elem}-results"]`).setAttribute('placeholder', $placeholder);
    //     });
    // });

    // property details page scrollspy
    var sections = $('.property-scrollspy');
    var navLinks = $('nav#property-scroll .nav-item a');

    $(window).on('scroll', function () {
        var curPos = $(this).scrollTop();

        const stickyNavBar = $('nav#property-scroll');
        if (stickyNavBar.length > 0) {
            const navBarStickyTop = stickyNavBar.offset().top;

            if (curPos >= navBarStickyTop) {
                // console.log('Element is sticky');
                stickyNavBar.addClass('active');
            } else {
                // console.log('Element is not sticky');
                stickyNavBar.removeClass('active');
            }
        }


        sections.each(function () {
            var top = $(this).offset().top - 100;
            var bottom = top + $(this).outerHeight();

            if (curPos >= top && curPos <= bottom) {
                navLinks.removeClass('active');
                $('nav a[href="#' + $(this).attr('id') + '"]').addClass('active');
            }
        });
    });
});

/**Image Gallery Start*/

// Select relevant HTML elements
// const filterButtons = document.querySelectorAll("#filter-buttons button");
// const filterableCards = document.querySelectorAll("#filterable-cards .card");

// // Function to filter cards based on filter buttons
// const filterCards = (e) => {
//     document.querySelector("#filter-buttons .active").classList.remove("active");
//     e.target.classList.add("active");

//     filterableCards.forEach(card => {
//         // show the card if it matches the clicked filter or show all cards if "all" filter is clicked
//         if(card.dataset.name === e.target.dataset.filter || e.target.dataset.filter === "all") {
//             return card.classList.replace("hide", "show");
//         }
//         card.classList.add("hide");
//     });
// }

// filterButtons.forEach(button => button.addEventListener("click", filterCards));

/**Image Gallery End */