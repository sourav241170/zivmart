function inextAlert(alert) {
    var html = '';

    var heading = (alert.heading) ? alert.heading : '';
    var subheading = (alert.subheading) ? alert.subheading : '';
    var text = (alert.text) ? alert.text : '';
    var status = (alert.status) ? alert.status : '';

    var alertColor = '';
    if (status) {
        alertColor = 'alert-';
        switch (status) {
            case 'success':
                alertColor += 'primary';
                break;
            case 'error':
                alertColor += 'danger';
                break;
            default:
                alertColor += 'primary';
        }
    }

    var textColor = '';
    if (status) {
        textColor = 'text-';
        switch (status) {
            case 'success':
                textColor += 'primary';
                break;
            case 'error':
                textColor += 'danger';
                break;
            default:
                textColor += 'primary';
        }
    }

    var bgColor = '';
    if (status) {
        bgColor = 'bg-light-';
        switch (status) {
            case 'success':
                bgColor += 'primary';
                break;
            case 'error':
                bgColor += 'danger';
                break;
            default:
                bgColor += 'primary';
        }
    }

    var border = '';
    // switch (alert.hasBorder) {
    //     case false:
    //         border = '';
    //         break;
    //     default:
    //         border = 'border border-';
    //         switch (status) {
    //             case 'success':
    //                 border += 'primary';
    //                 break;
    //             case 'error':
    //                 border += 'danger';
    //                 break;
    //             default:
    //                 border += 'primary';
    //         }
    // }

    switch (alert.hasBorder) {
        case true:
            border = 'border border-';
            switch (status) {
                case 'success':
                    border += 'primary';
                    break;
                case 'error':
                    border += 'danger';
                    break;
                default:
                    border += 'primary';
            }
            break;
        default:
            border = '';
    }

    html += '<div class="alert '+ alertColor +' alert-dismissible '+ border +' d-flex flex-column flex-sm-row">';
    html +=
        '<div class="d-flex flex-column pe-0 pe-sm-10">'+
            '<h5 class="'+ textColor +'">'+ heading +'</h5>'+
            '<span>'+ text +'</span>'+
        '</div>';

    if(alert.hasDismisible){
        html +=
            '<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">'+
                '<i class="fas fa-times text-primary"></i>'+
            '</button>';
    }

    html += '<div>';

    return html;
}
