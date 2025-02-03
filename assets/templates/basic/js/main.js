
"use strict";

// ==========================================
//      Start Document Ready function
// ==========================================
$(document).ready(function () {

  // ============== Header Hide Click On Body Js Start ========
  $('.header-button').on('click', function () {
    $('.body-overlay').toggleClass('show')
  });
  $('.body-overlay').on('click', function () {
    $('.header-button').trigger('click')
    $(this).removeClass('show');
  });
  // =============== Header Hide Click On Body Js End =========


  /*==================== custom dropdown select js ====================*/
  $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
    $(this).parent().toggleClass('open');
  });
  $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function () {
    $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
    $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this).html());
  });
  $(document).on('keyup', function (evt) {
    if ((evt.keyCode || evt.which) === 27) {
      $('.custom--dropdown').removeClass('open');
    }
  });
  $(document).on('click', function (evt) {
    if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
      $('.custom--dropdown').removeClass('open');
    }
  });

  /*=============== custom dropdown select js end =================*/

  // ========================== Header Hide Scroll Bar Js Start =====================
  $('.navbar-toggler.header-button').on('click', function () {
    $('body').toggleClass('scroll-hide-sm')
  });
  $('.body-overlay').on('click', function () {
    $('body').removeClass('scroll-hide-sm')
  });
  // ========================== Header Hide Scroll Bar Js End =====================

  // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
  $('.dropdown-item').on('click', function () {
    $(this).closest('.dropdown-menu').addClass('d-block')
  });
  // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

  // ========================== Add Attribute For Bg Image Js Start =====================
  $(".bg-img").css('background', function () {
    var bg = ('url(' + $(this).data("background-image") + ')');
    return bg;
  });
  // ========================== Add Attribute For Bg Image Js End =====================

  // ========================== add active class to ul>li top Active current page Js Start =====================
  function dynamicActiveMenuClass(selector) {
    let FileName = window.location.pathname.split("/").reverse()[0];

    selector.find("li").each(function () {
      let anchor = $(this).find("a");
      if ($(anchor).attr("href") == FileName) {
        $(this).addClass("active");
      }
    });
    // if any li has active element add class
    selector.children("li").each(function () {
      if ($(this).find(".active").length) {
        $(this).addClass("active");
      }
    });
    // if no file name return
    if ("" == FileName) {
      selector.find("li").eq(0).addClass("active");
    }
  }
  if ($('ul').length) {
    dynamicActiveMenuClass($('ul'));
  }
  // ================== add active class to ul>li top Active current page Js End =====================

  // login and sign up js start here ==========

  $('.with-email').on('click', function () {
    $(this).addClass('d-none')
    $('.email-system').removeClass('d-none');
  })
  // login and sign up js start here ==========


  /*========== account balance list js start here ==========*/

  $('.balance-range__item').on('click', function () {
    $('.balance-range__item').removeClass('active');
    $(this).addClass('active');
  })

  /*========== account balance list js end here ==========*/

  // ================== Password Show Hide Js Start ==========
  $(".toggle-password").on('click', function () {
    $(this).toggleClass(" fa-eye-slash");
    var input = $($(this).attr("id"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
  // =============== Password Show Hide Js End =================

  // ========================= Slick Slider Js Start ==============
  $('.testimonial-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 1500,
    dots: true,
    pauseOnHover: true,
    arrows: false,
    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          arrows: false,
          slidesToShow: 1,
          dots: true,
        }
      },
    ]
  });
  // ========================= Slick Slider Js End ===================

  // ========================= trading Slider Js Start ===============
  $('.trading-slider ').slick({
    arrows: false,
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    speed: 2000,
    cssEase: "linear",
    autoplay: true,
    autoplaySpeed: 0,
    adaptiveHeight: false,
    pauseOnDotsHover: false,
    pauseOnHover: true,
    pauseOnFocus: true,
    responsive: [
      {
        breakpoint: 1699,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 1399,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 575,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });
  // ========================= trading Slider Js End ===================

  //=========================== switch js ===============

  $('#switcher').on('click', function () {
    if ($('#evaluation').hasClass('toggler--is-active')) {
      $('#evaluation').removeClass('toggler--is-active');
      $('#scaling_wrapper').addClass('d-block').removeClass('d-none');
    } else {
      $('#evaluation').addClass('toggler--is-active');
      $('#scaling_wrapper').addClass('d-none').removeClass('d-block');
    }

    if ($('#scaling').hasClass('toggler--is-active')) {
      $('#scaling').removeClass('toggler--is-active');
      $('.evalation-main').addClass('d-block').removeClass('d-none');
    } else {
      $('#scaling').addClass('toggler--is-active');
      $('.evalation-main').addClass('d-none').removeClass('d-block');
    }
  });

  // ===========================switch js end ==============

  // ================== Sidebar Menu Js Start ===============
  // Sidebar Dropdown Menu Start
  $(".has-dropdown > a").on('click', function () {
    $(".sidebar-submenu").slideUp(200);
    if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".has-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".has-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });
  // Sidebar Dropdown Menu End

  // Sidebar Icon & Overlay js 
  $(".dashboard-body__bar-icon").on("click", function () {
    $(".sidebar-menu").addClass('show-sidebar');
    $(".sidebar-overlay").addClass('show');
  });
  $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
    $(".sidebar-menu").removeClass('show-sidebar');
    $(".sidebar-overlay").removeClass('show');
  });
  // Sidebar Icon & Overlay js 
  // ===================== Sidebar Menu Js End =================

  // ==================== Dashboard User Profile Dropdown Start ==================
  $('.user-info__button').on('click', function (event) {
    event.stopPropagation(); // Prevent the click event from propagating to the body
    $('.user-info-dropdown').toggleClass('show');
  });

  $('.user-info-dropdown__link').on('click', function (event) {
    event.stopPropagation(); // Prevent the click event from propagating to the body
    $('.user-info-dropdown').addClass('show')
  });

  $('body').on('click', function () {
    $('.user-info-dropdown').removeClass('show');
  })
  // ==================== Dashboard User Profile Dropdown End ==================


  // ========================= Odometer Counter Up Js End ==========
  $(".counterup-item").each(function () {
    $(this).isInViewport(function (status) {
      if (status === "entered") {
        for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
          var el = document.querySelectorAll('.odometer')[i];
          el.innerHTML = el.getAttribute("data-odometer-final");
        }
      }
    });
  });
  // ========================= Odometer Up Counter Js End =====================

  // ========================= Wrap All Tables in .table-responsive class Js Start =====================
  $('.table').each((index, table) => {
    $(table).wrap('<div class="table--responsive"></div>')
  })
  // ========================= Wrap All Tables in .table-responsive class Js End =====================

});
// ==========================================
//      End Document Ready function
// ==========================================

// ========================= Preloader Js Start =====================
$(window).on("load", function () {
  $('.preloader').fadeOut();
})



// ========================= Preloader Js End=====================

// ========================= Header Sticky Js Start ==============
$(window).on('scroll', function () {
  if ($(window).scrollTop() >= 200) {
    $('.header').addClass('fixed-header');
  }
  else {
    $('.header').removeClass('fixed-header');
  }
});
// ========================= Header Sticky Js End===================

//============================ Scroll To Top Icon Js Start =========
var btn = $('.scroll-top');

$(window).scroll(function () {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function (e) {
  e.preventDefault();
  $('html, body').animate({ scrollTop: 0 }, '300');
});
//========================= Scroll To Top Icon Js End ======================


let elements = document.querySelectorAll('[s-break]');

Array.from(elements).forEach(element => {
  let html = element.innerHTML;

  if (typeof html != 'string') {
    return false;
  }

  let position = parseInt(element.getAttribute('s-break'));
  let wordLength = parseInt(element.getAttribute('s-length'));


  html = html.split(" ");

  var firstPortion = [];
  var colorText = [];
  var lastPortion = [];

  if (position < 0) {
    colorText = html.slice(position);
    firstPortion = html.slice(0, position);
  } else {
    var lastWord = position + wordLength;
    colorText = html.slice(position, lastWord);
    firstPortion = html.slice(0, position);
    lastPortion = html.slice(lastWord, html.length);
  }

  var color = element.getAttribute('s-color') || "title-style";

  colorText = `<span class="${color}">${colorText.toString().replaceAll(',', ' ')}</span>`;

  firstPortion = firstPortion.toString().replaceAll(',', ' ');
  lastPortion = lastPortion.toString().replaceAll(',', ' ');

  element.innerHTML = `${firstPortion} ${colorText}  ${lastPortion}`;
});

let allowDecimal = window.allow_decimal || 6;

function showAmount(amount, decimal = allowDecimal, separate = true, exceptZeros = false) {
  let separator = '';
  if (separate) {
    separator = ',';
  }

  amount = parseFloat(amount).toFixed(decimal).split('.');
  let printAmount = amount[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
  printAmount = printAmount + '.' + amount[1];

  if (exceptZeros) {
    let exp = printAmount.split('.');
    if (Number(exp[1]) * 1 === 0) {
      printAmount = exp[0];
    } else {
      printAmount = printAmount.replace(/(\.[0-9]*[1-9])0+$/, '$1');
    }
  }
  return printAmount;
}

function getAmount(amount, decimal = 6) {
  return parseFloat(amount).toFixed(decimal);

}

$('.submit-form-on-change').on('change', function (e) {
  $(this).closest('form').submit();
});

function tableDataLabel() {
  Array.from(document.querySelectorAll('table')).forEach(table => {
    let heading = table.querySelectorAll('thead tr th');
    Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
      Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
        colum.setAttribute('data-label', heading[i] ? heading[i].innerText : '')
      });
    });
  });
}

tableDataLabel();