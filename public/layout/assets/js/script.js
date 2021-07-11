"use strict";
$(document).ready(function() {
    // card js start
    $(".card-header-right .close-card").on('click', function() {
        var $this = $(this);
        $this.parents('.card').animate({
            'opacity': '0',
            '-webkit-transform': 'scale3d(.3, .3, .3)',
            'transform': 'scale3d(.3, .3, .3)'
        });

        setTimeout(function() {
            $this.parents('.card').remove();
        }, 800);
    });
    $(".card-header-right .reload-card").on('click', function() {
        var $this = $(this);
        $this.parents('.card').addClass("card-load");
        $this.parents('.card').append('<div class="card-loader"><i class="fa fa-spinner rotate-refresh"></div>');
        setTimeout(function() {
            $this.parents('.card').children(".card-loader").remove();
            $this.parents('.card').removeClass("card-load");
        }, 3000);
    });
    $(".card-header-right .card-option .open-card-option").on('click', function() {
        var $this = $(this);
        if ($this.hasClass('fa-times')) {
            $this.parents('.card-option').animate({
                'width': '30px',
            });
            $(this).removeClass("fa-times").fadeIn('slow');
            $(this).addClass("fa-wrench").fadeIn('slow');
        } else {
            $this.parents('.card-option').animate({
                'width': '140px',
            });
            $(this).addClass("fa-times").fadeIn('slow');
            $(this).removeClass("fa-wrench").fadeIn('slow');
        }
    });
    $(".card-header-right .minimize-card").on('click', function() {
        var $this = $(this);
        var port = $($this.parents('.card'));
        var card = $(port).children('.card-block').slideToggle();
        $(this).toggleClass("fa-minus").fadeIn('slow');
        $(this).toggleClass("fa-plus").fadeIn('slow');
    });
    $(".card-header-right .full-card").on('click', function() {
        var $this = $(this);
        var port = $($this.parents('.card'));
        port.toggleClass("full-card");
        $(this).toggleClass("fa-window-restore");
    });

    $(".card-header-right .icofont-spinner-alt-5").on('mouseenter mouseleave', function() {
        $(this).toggleClass("rotate-refresh").fadeIn('slow');
    });
    $("#more-details").on('click', function() {
        $(".more-details").slideToggle(500);
    });
    $(".mobile-options").on('click', function() {
        $(".navbar-container .nav-right").slideToggle('slow');
    });
    $(".search-btn").on('click', function() {
        $(".main-search").addClass('open');
        $('.main-search .form-control').animate({
            'width': '200px',
        });
    });
    $(".search-close").on('click', function() {
        $('.main-search .form-control').animate({
            'width': '0',
        });
        setTimeout(function() {
            $(".main-search").removeClass('open');
        }, 300);
    });
    // $(".header-notification").on('click', function() {
    //     $(this).children('.show-notification').slideToggle(500);
    //     $(this).toggleClass('active');
    //
    // });

    $(document).ready(function(){
        $(".header-notification").click(function(){
            $(this).find(".show-notification").slideToggle(500);
            $(this).toggleClass('active');
        });
    });
    $(document).on("click", function(event){
        var $trigger = $(".header-notification");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".show-notification").slideUp(300);
            $(".header-notification").removeClass('active');
        }
    });

    // search
    $("#search-friends").on("keyup", function() {
        var g = $(this).val().toLowerCase();
        $(".userlist-box .media-body .chat-header").each(function() {
            var s = $(this).text().toLowerCase();
            $(this).closest('.userlist-box')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
        });
    });

    // open chat box
    $('.displayChatbox').on('click', function() {
        var my_val = $('.pcoded').attr('vertical-placement');
        if (my_val == 'right') {
            var options = {
                direction: 'left'
            };
        } else {
            var options = {
                direction: 'right'
            };
        }
        $('.showChat').toggle('slide', options, 500);
    });

    //open friend chat
    $('.userlist-box').on('click', function() {
        var my_val = $('.pcoded').attr('vertical-placement');
        if (my_val == 'right') {
            var options = {
                direction: 'left'
            };
        } else {
            var options = {
                direction: 'right'
            };
        }
        $('.showChat_inner').toggle('slide', options, 500);
    });
    //back to main chatbar
    $('.back_chatBox').on('click', function() {
        var my_val = $('.pcoded').attr('vertical-placement');
        if (my_val == 'right') {
            var options = {
                direction: 'left'
            };
        } else {
            var options = {
                direction: 'right'
            };
        }
        $('.showChat_inner').toggle('slide', options, 500);
        $('.showChat').css('display', 'block');
    });
    $('.back_friendlist').on('click', function() {
        var my_val = $('.pcoded').attr('vertical-placement');
        if (my_val == 'right') {
            var options = {
                direction: 'left'
            };
        } else {
            var options = {
                direction: 'right'
            };
        }
        $('.p-chat-user').toggle('slide', options, 500);
        $('.showChat').css('display', 'block');
    });
    // /*chatbar js end*/

    $('[data-toggle="tooltip"]').tooltip();

    // wave effect js
    Waves.init();
    Waves.attach('.flat-buttons', ['waves-button']);
    Waves.attach('.float-buttons', ['waves-button', 'waves-float']);
    Waves.attach('.float-button-light', ['waves-button', 'waves-float', 'waves-light']);
    Waves.attach('.flat-buttons', ['waves-button', 'waves-float', 'waves-light', 'flat-buttons']);
});
$(document).ready(function() {
    $(".theme-loader").animate({
        opacity: "0"
    },1000);
    setTimeout(function() {
        $(".theme-loader").remove();
    }, 800);
});

// toggle full screen
function toggleFullScreen() {
    var a = $(window).height() - 10;

    if (!document.fullscreenElement && // alternative standard method
        !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}

$('body').append('' +
    '<div class="fixed-button">' +
        '<a href="#" class="btn btn-md btn-primary">' +
            '<i class="fa fa-angle-up" aria-hidden="true"></i>' +
        '</a> ' +
    '</div>' +
'');
var $window = $(window);
var nav = $('.fixed-button');
$window.scroll(function() {
    if ($window.scrollTop() >= 200) {
        nav.addClass('active');
    } else {
        nav.removeClass('active');
    }
});
nav.on('click', (e) => {
    e.preventDefault();
    $('html, body').animate({scrollTop: 0}, 'fast');
})

const fileInput = document.querySelector('#upload')
const selected = document.querySelector('.fileName-student')
var submitForm = false;
$(fileInput).on('change', function (event){
    if (event.target.files.length > 0) {
        selected.innerHTML = `<span>Fichier choisi :</span><span style="color: blue">${event.target.files[0].name}</span>`;
        if (submitForm) {
            $(fileInput).closest('.form-group.has-error').find('span.error').remove();
            $(fileInput).closest('.form-group.has-error').removeClass('has-error');
        }
    } else {
        if (submitForm) {
            $(fileInput).closest('.form-group').addClass('has-error');
            $(fileInput).closest('.form-group.has-error').append('<span id="upload-error" class="error">Ce champ est obligatoire.</span>');
        }
        selected.innerHTML = '';
    }
})
$("#form_subject").on('submit', () => {
    submitForm = true;
});
if (document.querySelector('#form_subject'))
$("#form_subject").validate({
    errorElement: 'span',
    messages: {
        file: {
            required: "Un fichier est requis"
        }
    },
    errorPlacement: function (error, element) { // render error placement for each input type
        if (element.attr('name') === 'publication_student[explication_visio]')
        {
            error.insertAfter($('input[name="publication_student[explication_visio]"]').parent());
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function (element) { // hightlight error inputs
        if ($(element).is(':checkbox')) {
            $(element)
                .closest('.form-check').addClass('has-error');
        }
        $(element)
            .closest('.form-group').addClass('has-error'); // set error class to the control group

    },
    unhighlight: function (element) { // revert the change done by hightlight
        if ($(element).is(':checkbox')) {
            $(element)
                .closest('.form-check').removeClass('has-error');
        }
        $(element)
            .closest('.form-group').removeClass('has-error'); // set error class to the control group
    },
    success: function (label) {
        if ($(label).is(':checkbox')) {
            $(label)
                .closest('.form-check').removeClass('has-error');
        }
        label
            .closest('.form-group').removeClass('has-error'); // set success class to the control group
    },
    submitHandler: function (form) {
        form.submit();
    }
});

const fetchAction = async (url, data = {}) => {
    const response = await fetch(url, {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
            'content-type': 'application/json',
        },
        method: 'POST',
        body: JSON.stringify(data),
    });
    if (response.ok) {
        return response.json();
    }
    const error = await response.json();
    throw new Error(error.message);
};

$('input.check-state').on('click', async (e) => {
    e.preventDefault();
    if (!window.confirm('voulez-vous changer le statut ?')) return;
    try {
        const id = e.target.dataset.id;
        const response = await fetchAction(`/setting/subject/change-state/${id}`, { value: e.target.checked });
        e.target.checked = response.result;
    } catch (error) {
        alert('erreur')
    }
})
