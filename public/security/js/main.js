
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input100, .input50').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() !== "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })
    })

    /*==================================================================
    [ Radio Type ]*/
    $('.wrap-input-radio').each(function () {
        const wrap = $(this);
        wrap.find('.container-radio input[type="radio"]').each(function () {
            const $this = $(this);
            $this.closest('.container-radio').css('visibility', 'hidden');
            const label = $this.next('label');
            label.addClass('radio');
            const span = document.createElement('span');
            span.className = 'label';
            label.prepend(span);
            $this.prependTo(label);
            $this.addClass('hidden');
            label.appendTo(wrap);
        })
    })

    /*==================================================================
    [ Validate ]*/
    var input = $('.validate-input .input100, .validate-input .input50, .radio, input[type="checkbox"][name="cgu"]');
    var form = $('.validate-form');
    form.attr('novalidate', true);

    form.on('submit', function(e){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) === false){
                showValidate(input[i]);
                check=false;
            }
        }

        if ($('.alert-validate').length > 0) {
            e.preventDefault();
        }

        $('.validate-form .input100, .validate-form .input50').each(function(){
            var $this = this;
            $($this).on('focusout', function() {
                if (validate($this) === false) {
                    showValidate(this);
                }
            });
        });
        if( document.forms["registration_study_pro"]){
            //Pour Etudiant Professionnel
            if ($('input[name="registration_study_pro[level_study_occuped]"]:checked').length === 0) {
                showValidate(document.querySelector('input[name="registration_study_pro[level_study_occuped]"]').parentElement);
            } else {
                hideValidate(document.querySelector('input[name="registration_study_pro[level_study_occuped]"]:checked').parentElement)
            }
        } else {
            if ($('input[name="registration_form[level_study]"]:checked').length === 0) {
                showValidate(document.querySelector('input[name="registration_form[level_study]"]').parentElement);
            } else {
                hideValidate(document.querySelector('input[name="registration_form[level_study]"]:checked').parentElement)
            }
        }

        $('.validate-form input[type="checkbox"][name="cgu"]').each(function(){
            var $this = this;
            $($this).change(function() {
                if (validate($this) !== false) {
                    hideValidate(this);
                } else {
                    showValidate(this);
                }
            });
        });

        if ($('.alert-validate').length === 0) {
            e.target.submit();
        }

        return check;
    });


    $('.validate-form .input100, .validate-form .input50').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });

    $('.validate-form input[type="checkbox"][name="cgu"]').each(function(){
        $(this).change(function(){
            hideValidate(this);
        });
    });

    function validate (input) {
        if($(input).attr('type') === 'email' || $(input).attr('name') === 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if ($(input).attr('type') === "checkbox") {
                return input.checked;
            }
            //Pour professionnnel
            if ($(input).attr('type') === "radio") {
                return input.checked;
            }

            var passwords = $('input[type="password"]');
            $(passwords[1]).parent().data('validate', 'Ce champ est requis');
            if($(input).val().trim() === '') {
                return false;
            }
            if ($(input).attr('type') === 'password') {
                if ($(passwords[0]).val() !== $(passwords[1]).val() && $(passwords[1]).val().trim() !== '') {
                    $(passwords[1]).parent().attr('data-validate', 'Le mot de passe ne correspond pas');
                    showValidate(passwords[1]);
                } else {
                    hideValidate(passwords[1]);
                }
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }

    $('#general_conditions').modal({show: true, backdrop: 'static', keyboard: false});

    if(jQuery().fileinput) {
        var btnBrowse = '<button class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i> choisir un profil</button>'
        $("#registration_form_picture_student, #registration_study_pro_picture_student").fileinput({
            overwriteInitial: true,
            maxFileSize: 1500,
            showClose: false,
            showCaption: false,
            browseLabel: '',
            removeLabel: '',
            browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
            removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
            removeTitle: 'Cancel or reset changes',
            elErrorContainer: '#kv-avatar-errors-1',
            msgErrorClass: 'alert alert-block alert-danger',
            defaultPreviewContent: '<img src="https://liberate.gg/wp-content/uploads/blankAvatar-e1614094553909.jpg" alt="Votre photo de profil">',
            layoutTemplates: {main2: `{preview} {remove} ${btnBrowse}`},
            allowedFileExtensions: ["jpg", "png"]
        });
    }

    jQuery(document).ready(function () {
        const data = [{
            id: 'other',
            text: 'autre'
        }]
        $('#specialities').select2({
            placeholder: 'Choisir vos matières',
            width: '100%',
            data: data,
            maximumSelectionLength: 3,
            theme: 'bootstrap'
        });
        $('#specialities').on('select2:selecting', function (e) {
            const d = e.params.args.data;
            if (d.id === 'other') {
                e.preventDefault();
                const text = prompt('saisissez la matière: ');
                if (text === null) return;
                const newOption = new Option(text, text, false, true);
                $('#specialities').append(newOption);
            }
        });
    });
})(jQuery);
