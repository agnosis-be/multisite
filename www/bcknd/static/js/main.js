function ag_ToggleLock(strMsg) {
    if (typeof(strMsg) != 'undefined' && strMsg.length > 0) {
        document.getElementById('MsgTxt').innerHTML = strMsg;
        document.getElementById('Lock').style.display = 'block';
        document.getElementById('Msg').style.display = 'block';
    } else {
        document.getElementById('Lock').style.display = 'none';
        document.getElementById('Msg').style.display = 'none';
    }
}

function ag_ToggleControl(arrCtrl, objForm) {
    for (var i in arrCtrl) {
        var eCheckbox = objForm.elements[i];
        var eCtrl = objForm.elements[arrCtrl[i]];
        if (eCheckbox && eCtrl) {
            if (eCheckbox.checked) {
                eCtrl.disabled = false; 
            } else {
                eCtrl.disabled = true;
            }
        }
    }
}
