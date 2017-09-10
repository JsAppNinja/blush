function updateViewportDimensions() {
    var w = window, d = document, e = d.documentElement, g = d.getElementsByTagName('body')[0], x = w.innerWidth || e.clientWidth || g.clientWidth, y = w.innerHeight || e.clientHeight || g.clientHeight;
    return {width: x, height: y}
}

function mobilecheck() {
    var check = false;
    (function (a) {
        if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4)))check = true
    })(navigator.userAgent || navigator.vendor || window.opera);
    return check;
}

jQuery(document).ready(function ($) {
    $('p').each(function() {
        var $this = $(this);
        if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
            $this.remove();
    });

    $.ajaxSetup({
        cache: false
    });
    $.ajax('/app/rest/users/session/', {
        type: 'GET',
        dataType: 'json',
        complete: function (jqXHR, textStatus) {
            if (textStatus == "success") {
                $("li.login a").text('My Account').attr('href', '/app/dashboard').attr('data-my-account', true);
                $("li.signup").hide();
            }
        }
    });

    $(".post .body img").addClass('img-responsive').closest('.wp-caption').removeAttr('style');

    $("#header li.login a").click(function () {
        if (!this.hasAttribute('data-my-account')) {
            viewport = updateViewportDimensions();
            if (!mobilecheck() && viewport.width && viewport.width > 991) {
                $("#login-box").toggle();
                return false;
            }
        }
    });

    jQuery("#coach-slides").cycle({
        fx: 'scrollHorz',
        timeout: 0,
        height: 365,
        next: '#coach-slides-next',
        prev: '#coach-slides-prev'
    });

    $("#testimonial-slides").cycle({
        fx: 'scrollHorz',
        timeout: 10000,
        height: 210,
        pager: '#testimonials-nav .links'
    });

    $("#testimonial-mobile-slides").cycle({
        fx: 'scrollHorz',
        timeout: 10000,
        height: 210,
        pager: '#testimonials-mobile-nav .links'
    });

    /*$("#feature-slides").cycle({
     fx: 'scrollHorz',
     timeout:10000,
     height:210,
     pager:'#testimonials-nav .links'
     });*/

    $(".contact-us-button").click(function () {
        app.load_contact_modal();
    });

    $(".tell-story-button").click(function () {
        app.load_tell_story_modal();
    });

    $("#btn-dear-blush").click(function () {
        app.load_dear_blush_modal();
        return false;
    });

    $("#btn-career-apply").click(function () {
        app.load_apply_job_modal();
        return false;
    });

    $("#modal-video").on('show.bs.modal', function (e) {
        var url = $(e.relatedTarget).attr('data-url');
        $(this).attr('data-url', url);
    });

    $("#modal-video").on('shown.bs.modal', function (e) {
        var current_url = $(this).attr('data-current-url');
        var url = $(this).attr('data-url');
        if (current_url != url) {
            $(this).find('.modal-body').html('<iframe scrolling="no" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen src="' + url + '"  width ="640" height="360"></iframe>');
        }
        $(this).attr('data-current-url', url);
    });

    $(document).on("click", ".nav-toggle", function (e) {
        $(this).toggleClass("active");
        $("#menu-top").slideToggle(250);
        e.preventDefault();
    });

    $(document).on("click", "#menu-top li a", function (e) {
        viewport = updateViewportDimensions();
        if (viewport.width <= 991) {
            $(".nav-toggle").toggleClass("active");
            $("#menu-top").slideToggle(250);
        }
    });
});
