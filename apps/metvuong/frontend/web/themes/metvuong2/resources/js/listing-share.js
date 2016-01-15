/**
 * Created by Nhut Tran on 1/14/2016.
 */
(function($) {
// share-item click event
    $(document).on('click', '.share-item-1', function () {
        var _this = $(this);
        $('#share_form_1 input[type=text]').val(null);
        $('#share_form_1 textarea').val(null);
        var _address = $('#share_form_1 ._address');
        var _detailUrl = $('#share_form_1 ._detailUrl');
        var _image = $('#share_form_1 ._image');
        if (_address != null && _detailUrl != null) {
            _address.val(_this.attr("data-address"));
            _detailUrl.val(_this.attr("data-url"));
            _image.val(_this.attr("data-img"));
        }
    });

    $(document).on('click', '#share_form_1 button.send_mail', function () {
        var recipient_email = $('#share_form_1 .recipient_email').val();
        var your_email = $('#share_form_1 .your_email').val();
        if (recipient_email != null && your_email != null) {
            $('#box-share-1').modal('hide');
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#share_form_1').attr('action'),
                    data: $('#share_form_1').serializeArray(),
                    success: function (data) {
                        if (data.status == 200) {

                        }
                        else {
                            var strMessage = '';
                            $.each(data.parameters, function (idx, val) {
                                var element = 'share_form_1_' + idx;
                                strMessage += "\n" + val;
                            });
                            alert(strMessage + "\nTry again");
                        }
                    },
                    error: function () {
                        var strMessage = '';
                        $.each(data.parameters, function (idx, val) {
                            var element = 'change-pass-form-' + idx;
                            strMessage += "\n" + val;
                        });
                        alert(strMessage);
                    }
                });
            }, 1000);
        }
        return false;
    });

// share product link to facebook
    $(document).on('click', '#share_form_1 a.fb-icon', function () {
        var detailUrl = $('#share_form_1 ._detailUrl').val();
        if (detailUrl == null || detailUrl == '')
            detailUrl = $('#share_form_1 ._domain').val();

        FB.ui({
            method: 'share',
            href: detailUrl
        }, function (response) {
        });
    });

    $(document).on('click', '#share_form_1 a.twe-icon', function () {
        location.href = 'skype:nhuttranm?chat';
    });


    $(document).on('click', '.report_modal', function (e) {
        e.preventDefault();
        var _this = $(this);
        var _user_id = _this.attr('data-uid');
        if (_user_id == 0) {
            $('#frmLogin').modal('show');
            //$('#report-listing').modal('hide');
        } else {
            var _url = _this.attr('data-url');
            console.log(_url);
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: _url,
                    data: {user_id: _user_id},
                    success: function (data) {
                        if (data.statusCode == 200) {

                        } else if (data.statusCode == 404) {
                            alert(data.parameters.msg);
                        }
                    }
                });
            }, 1000);
        }
    });

    $(document).on('click', '#frm-report .send_report', function (e) {
        e.preventDefault();
        var _this = $(this);
        var _user_id = $('#frm-report #uid').val();
        if (_user_id != 0) {
            clearTimeout(timer);
            timer = setTimeout(function () {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: $('#frm-report').attr('action'),
                    data: $('#frm-report').serializeArray(),
                    success: function (data) {
                        $('#report-listing').modal('hide');
                        if (data == 200) {
                            alert("Your message is sent.\nThank you.");
                        }
                        else if (data == 13) {
                            alert("You reported with us");
                        }
                        else {
                            alert("Please, try again!");
                        }
                    },
                    error: function () {
                        $('#report-listing').modal('hide');
                    }
                });
            }, 500);
        }
        return false;
    });
})(jQuery);