var Tools = {
    // loans function
    loan_calculate : function () {

        var theoThoiGian       = document.getElementById('theoThoiGian').value;
        var ww = document.getElementById('theoThoiGian').selectedIndex;
        var tenTheoThoiGian       = document.getElementById('theoThoiGian').options[ww].text;

        var hinhThucTraNo       = document.getElementById('hinhThucTraNo').value;
        var w = document.getElementById('hinhThucTraNo').selectedIndex;
        var tenHinhThucTraNo       = document.getElementById('hinhThucTraNo').options[w].text;
        var knHinhThucTraNo    ="";
        var soTienVay           = document.getElementById('soTienVay').value;

        var laiSuat             = document.getElementById('laiSuat').value;
        if(theoThoiGian=='nam') {laiSuat=laiSuat/12;}

        var thoiGianVay         = document.getElementById('thoiGianVay').value;

        var ngayBatDau          = new Date(document.getElementById('ngayBatDau').value).getTime();

        var check = true;


        if(hinhThucTraNo == 0){

            alert('Chọn hình thức trả nợ');

            document.getElementById('hinhThucTraNo').focus();

            return false;}



        if(soTienVay == ''){

            alert('Nhập số tiền vay');

            document.getElementById('soTienVay').focus();

            return false;

        }



        if(laiSuat == ''){

            alert('Nhập lãi suất');

            document.getElementById('laiSuat').focus();

            return false;

        }



        if(thoiGianVay == ''){

            alert('Nhập thời gian vay');

            document.getElementById('thoiGianVay').focus();

            return false;

        }



        if(check){

            var tempHinhThucTraNo = '';

            var laiSuatTungThang  = (laiSuat*laiSuat)/100;

            var tienTraHangTHang  = '';

            var tongLaiSuat       = 1;

            var temp              = '<table class="savings-tbl"><tr class="savings-tlt">';
            if (isInt(ngayBatDau)) {
                temp += '<td>Ngày thanh toán</td>';
                var nextTime = ngayBatDau;
                document.getElementById('inNgayBatDauVay').innerHTML          = document.getElementById('ngayBatDau').value;
                ngayBatDau += 86400000*30;
            }
            else {
                document.getElementById('inNgayBatDauVay').innerHTML          = 'Không xác định';
            }
            temp += '<td>Kỳ hạn(Tháng)</td><td>Tổng tiền phải trả</td><td>Tiền gốc</td><td>Tiền lãi</td><td>Tổng còn lại</td><td>Lãi suất thật(%)</td></tr>';

            var tienPhaiTra       = 0;

            var tempLL               = laiSuat/100;

            var traLai              = 0;

            var traGoc              = 0;

            var conLai              = 0;

            var tongTienPhaiTra      = 0;

            var tienPhaiTraHT       = 0;

            var traLaiHT          = 0;

            var traGocHT          = 0;

            var conLaiHT          = 0;

            var tongTraLai          = 0;

            var tongTraGoc          = 0;

            var laiSuatThat       = 0;

            if(hinhThucTraNo == 1){

                for(var i=1;i<=thoiGianVay;i++){

                    tongLaiSuat *= (1 + tempLL);

                }

                var tongCuoiKyHT = (soTienVay*tongLaiSuat)/thoiGianVay;

                traGocHT = Math.round(soTienVay/thoiGianVay);

                tienPhaiTraHT =  Math.round(tongCuoiKyHT-traLaiHT);

                traLaiHT = tienPhaiTraHT - traGocHT;

                conLaiHT = soTienVay;

                laiSuatThat = laiSuat;

                for(var a=1;a<=thoiGianVay;a++){

                    if(a ==1){

                        laiSuatThat = (traLaiHT/soTienVay)*100;

                    }else{

                        laiSuatThat = (traLaiHT/conLaiHT)*100;

                    }

                    laiSuatThat = laiSuatThat.toFixed(2);

                    conLaiHT -= traGocHT;

                    if(a==thoiGianVay){

                        conLaiHT = 0;

                    }

                    temp += '<tr><td class="saving_table saving_table_left">'+a+'</td><td class="saving_table">'+FormatNumber(tienPhaiTraHT)+'</td><td class="saving_table">'+FormatNumber(traGocHT)+'</td><td class="saving_table">'+FormatNumber(traLaiHT)+'</td><td class="saving_table">'+FormatNumber(conLaiHT)+'</td><td aling="center" class="saving_table saving_header_right">'+(laiSuatThat)+'</td></tr>';

                    tongTraGoc         += traGocHT;

                    tongTraLai      += traLaiHT;

                    tongTienPhaiTra += tienPhaiTraHT;

                }
                knHinhThucTraNo=document.getElementById('kn_1').innerHTML;

            }
            else if(hinhThucTraNo == 2){

                traGocHT      = Math.floor(soTienVay/thoiGianVay);

                traLaiHT      = Math.floor(soTienVay*tempLL);

                tienPhaiTraHT = Math.floor((traGocHT+traLaiHT));

                conLaiHT      = soTienVay;

                for(var a=1;a<=thoiGianVay;a++){

                    if(a ==1){

                        laiSuatThat = (traLaiHT/soTienVay)*100;

                    }else{

                        laiSuatThat = (traLaiHT/conLaiHT)*100;

                    }

                    laiSuatThat = laiSuatThat.toFixed(2);

                    conLaiHT -= Math.floor(traGocHT);

                    if(a==thoiGianVay){

                        conLaiHT = 0;

                    }
                    temp += '<tr>';
                    if (isInt(ngayBatDau)) {
                        nextTime = ngayBatDau +  a * 86400000 * 30;
                        nextTime = new Date(nextTime);
                        temp += '<td>'+nextTime.getDate()+'/'+nextTime.getMonth()+'/'+nextTime.getFullYear()+'</td>';
                    }
                    temp += '<td>'+a+'</td><td>'+FormatNumber(tienPhaiTraHT)+'</td><td>'+FormatNumber(traGocHT)+'</td><td>'+FormatNumber(traLaiHT)+'</td><td>'+FormatNumber(conLaiHT)+'</td><td>'+(laiSuatThat)+'</td></tr>';
                }

                tongTraGoc = traGocHT*thoiGianVay;

                tongTraLai = traLaiHT*thoiGianVay;

                tongTienPhaiTra = (traGocHT+traLaiHT)*thoiGianVay;
                knHinhThucTraNo=document.getElementById('kn_2').innerHTML;

            }
            else if(hinhThucTraNo == 3){

                for(var i=1;i<=thoiGianVay;i++){

                    tongLaiSuat *= (1 + tempLL);

                }

                var tempA = (soTienVay*tempLL)*tongLaiSuat;

                var tempB = (tongLaiSuat-1);

                tienPhaiTra = tempA/tempB;

                for(var a=1;a<=thoiGianVay;a++){

                    if(a==1){

                        traLai = soTienVay*tempLL;

                        laiSuatThat = (traLai/soTienVay)*100;

                    }else{

                        traLai = conLai*tempLL;

                        laiSuatThat = (traLai/conLai)*100;

                    }

                    laiSuatThat = laiSuatThat.toFixed(2);

                    traGoc = tienPhaiTra - traLai;

                    (a==1) ? conLai = soTienVay-traGoc : conLai -= traGoc;

                    traLaiHT = Math.round(traLai);

                    traGocHT = Math.round(traGoc);

                    conLaiHT = Math.round(conLai);

                    tienPhaiTraHT  = Math.round(tienPhaiTra);

                    if(a==thoiGianVay){

                        conLaiHT = 0;

                    }
                    temp += '<tr>';
                    if (isInt(ngayBatDau)) {
                        nextTime = ngayBatDau +  a * 86400000 * 30;
                        nextTime = new Date(nextTime);
                        temp += '<td>'+nextTime.getDate()+'/'+nextTime.getMonth()+'/'+nextTime.getFullYear()+'</td>';
                    }
                    temp += '<td class="saving_table saving_table_left">'+a+'</td><td class="saving_table">'+FormatNumber(tienPhaiTraHT)+'</td><td class="saving_table">'+FormatNumber(traGocHT)+'</td><td class="saving_table">'+FormatNumber(traLaiHT)+'</td><td class="saving_table">'+FormatNumber(conLaiHT)+'</td><td class="saving_table saving_header_right">'+(laiSuatThat)+'</td></tr>';

                    tongTraGoc += Math.round(traGoc);

                    tongTraLai += Math.round(traLai);

                }

                tongTienPhaiTra = Math.round(tienPhaiTra*thoiGianVay);
                knHinhThucTraNo=document.getElementById('kn_3').innerHTML;

            }
            else if(hinhThucTraNo == 4){

                traGocHT      = Math.round(soTienVay/thoiGianVay);

                conLaiHT = soTienVay;

                for(var a=1;a<=thoiGianVay;a++){

                    (a==1) ? traLaiHT = soTienVay*tempLL : traLaiHT = Math.round(conLaiHT*tempLL);

                    if(a ==1){

                        laiSuatThat = (traLaiHT/soTienVay)*100;

                    }else{

                        laiSuatThat = (traLaiHT/conLaiHT)*100;

                    }

                    laiSuatThat = laiSuatThat.toFixed(2);

                    conLaiHT -= Math.round(traGocHT);

                    tienPhaiTraHT = Math.round((traGocHT+traLaiHT));

                    if(a==thoiGianVay){

                        conLaiHT = 0;

                    }

                    temp += '<tr>';
                    if (isInt(ngayBatDau)) {
                        nextTime = ngayBatDau +  a * 86400000 * 30;
                        nextTime = new Date(nextTime);
                        temp += '<td>'+nextTime.getDate()+'/'+nextTime.getMonth()+'/'+nextTime.getFullYear()+'</td>';
                    }
                    temp += '<td class="saving_table saving_table_left">'+a+'</td><td class="saving_table">'+FormatNumber(tienPhaiTraHT)+'</td><td class="saving_table">'+FormatNumber(traGocHT)+'</td><td class="saving_table">'+FormatNumber(traLaiHT)+'</td><td class="saving_table">'+FormatNumber(conLaiHT)+'</td><td class="saving_table saving_header_right">'+(laiSuatThat)+'</td></tr>';

                    tongTraLai += Math.round(traLaiHT);

                    tongTienPhaiTra += tienPhaiTraHT;

                }

                tongTraGoc = traGocHT*thoiGianVay;
                knHinhThucTraNo=document.getElementById('kn_4').innerHTML;

            }



            temp += '<tr class="savings-tlt">';
            if (isInt(ngayBatDau)) {
                temp += '<td colspan="2">Tổng số</td>';
            }
            else {
                temp += '<td>Tổng số</td>';
            }
            temp += '<td>'+FormatNumber(tongTienPhaiTra)+'</td><td>'+FormatNumber(tongTraGoc)+'</td><td>'+FormatNumber(tongTraLai)+'</td><td></td><td></td></tr>';
            temp+="</table>"


            document.getElementById('inSoTienVay').innerHTML              = FormatNumber(soTienVay)+' VND';

            document.getElementById('inThoiGianVay').innerHTML             = thoiGianVay+' Tháng';

            document.getElementById('inLaiXuatThucPhaiTra').innerHTML    = laiSuat+' % /'+'Tháng';

            document.getElementById('inHinhThucTraNo').innerHTML    = tenHinhThucTraNo;

            document.getElementById('inTongSoTienPhaiTra').innerHTML     = FormatNumber(tongTienPhaiTra)+' VND';

            document.getElementById('inKetQua').innerHTML                 = temp;

            document.getElementById('kn_detail').innerHTML                 = knHinhThucTraNo;
        }
    }
};

function explode (delimiter, string, limit) {
    var emptyArray = {
        0: ''
    };
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
        return null;
    }
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }
    if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        var splitted = string.toString().split(delimiter.toString());
        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }
}
function FormatNumber(strNumber)
{
    var arStrNumber=explode('.',strNumber,2);
    var flag=1;
    var strResult='';
    var strFist=arStrNumber[0].split('');

    for(i=strFist.length-1;i>=0;i--){
        if((flag%3)==0 && i!=0){
                strResult=','+strFist[i]+strResult;
        }else{
            strResult=strFist[i]+strResult;
        }
        flag++;
    }
    if(arStrNumber[1].length>0) {
        strResult=strResult+'.'+arStrNumber[1];
    }
    return strResult;
}

function isInt(n){
    return typeof n== "number" && isFinite(n) && n%1===0;
}