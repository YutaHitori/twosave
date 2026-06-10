const x = document.getElementById("x");
const xv = x.value;
const y = document.getElementById("y");
const yv = y.value;
const preview = document.getElementById("preview");
const zoom = document.getElementById("zoom");
const zv = zoom.value;
const uploadForm = document.getElementById("uploadForm");
const notif = document.getElementById('notif');

let width, height, xdif = 0, ydif = 0;

function changeName() {
    const editName = document.getElementById("editName");
    const editNameVal = document.getElementById("editNameVal");
    editName.reset();
    editNameVal.inert = !editNameVal.inert;
    editNameVal.select();
}

function getSize() {
    width = preview.width;
    height = preview.height;
    const isWide = width >= height;
    preview.style.height = isWide ? '100%' : 'auto';
    preview.style.width  = isWide ? 'auto' : '100%';
    width = preview.width;
    height = preview.height;
    xdif = isWide ? (height - width) / 4 : 0;
    ydif = isWide ? 0 : (width - height) / 4;
    preview.style.marginLeft = isWide ? `${xdif}%` : '0';
    preview.style.marginTop  = isWide ? '0' : `${ydif}%`;
    update();
}

function uploadPP() {
    document.getElementById('uploadPP').checked = !document.getElementById('uploadPP').checked;
    uploadForm.hidden = !uploadForm.hidden;
    getSize();
}

function previewImage(event,inputE) {
    var proceed = true;
    var errtext = '';
    var errnum = 0;
    const file = event.target.files[0];
    if (!file) {
        inputE.files = previewImage.last;
        return;
    }
    const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        errtext += "Unsupported or Invalid Image File";
        errnum += 1;
    }
    if (file.size > 3 * 1024 * 1024) {
        if (errtext != '') errtext += "<br>";
        errtext += "Image Size is Too Large (max 3MB)";
        errnum += 1;
    }
    errnum = errnum > 1 ? `(${errnum})` : '';
    if (errtext != '') {
        notif.innerHTML = `<span class='notif' id='dn'><h3>WARNING! ${errnum}</h3>${errtext}</span>`;
        proceed = false;
    }
    if (proceed) {
        preview.src = URL.createObjectURL(file);
        preview.onload = () => {
            URL.revokeObjectURL(preview.src);
            getSize();
        };
        previewImage.last = inputE.files;
        notif.innerHTML = "<span class='notif' id='dn'><h3>Notification!</h3>Image Uploaded Successfully</span>";
    } else {
        inputE.value = '';
        inputE.files = previewImage.last;
    }
}

function update() {
    const z = zoom.value;
    preview.style.right = (x.value - 50) * ((z - 1) + (xdif / -50) * z) + "%";
    preview.style.bottom = (y.value - 50) * ((z - 1) + (ydif / -50) * z) + "%";
    preview.style.scale = z;
    document.getElementById('style').value = preview.style.cssText;
}

function revert() {
    x.value = xv;
    y.value = yv;
    zoom.value = zv;
    update();
}

function showPass(a) {
    const x = document.getElementById("password");
    x.type = x.type == "text" ? 'password' : 'text';
    if (a) {
        const y = document.getElementById("Cpassword");
        y.type = y.type == "text" ? 'password' : 'text';
    }
    checkIcon();
}

function checkIcon() {
    const checkIcon = document.getElementById("checkIcon");
    checkIcon.innerHTML = checkIcon.innerHTML == '✔' ? '' :'✔';
}

function checkPass() {
    let canSubmit = true;
    const password = document.getElementById("password").value;
    let msg = '';
    if (password.length > 0) {
        if (password.length < 8) {
            msg += "*Password Must Be At Least 8 Characters Long.<br>";
            canSubmit = false;
        }
        if (!/[0-9]/.test(password)) {
            msg += "*Password Must Contain At Least One Number.<br>";
            canSubmit = false;
        }
        if (!/[A-Z]/.test(password)) {
            msg += "*Password Must Contain At Least One Uppercase Letter.";
            canSubmit = false;
        }
    }
    document.getElementById('passReqMsg').innerHTML = msg;
}

window.addEventListener('DOMContentLoaded', () => {
    document.querySelector('form').addEventListener('submit', validateForm);
});

function validateForm(event,isR) {
    const EEM = document.getElementById("EEM");
    const PEM = document.getElementById("PEM");
    if (isR) {
        const NEM = document.getElementById("NEM");
        const CPEM = document.getElementById("CPEM");
        checkPass();
        if (document.getElementById("name").value.length == 0) {
            canSubmit = false;
            NEM.innerHTML = '*Required';
        } else NEM.innerHTML = '';
        if (document.getElementById("Cpassword").value == 0) {
            canSubmit = false;
            CPEM.innerHTML = '*Required';
        } else CPEM.innerHTML = '';
    }
    
    if (document.getElementById("email").value.length == 0) {
        canSubmit = false;
        EEM.innerHTML = '*Required';
    } else EEM.innerHTML = '';
    if (document.getElementById("password").value == 0) {
        canSubmit = false;
        PEM.innerHTML = '*Required';
    } else PEM.innerHTML = '';
    
    if (!canSubmit) event.preventDefault();
    canSubmit = true;
}

function hideBalance(str) {
    const hideEye = document.getElementById("hideEye");
    const bal = document.getElementById("bal");
    if (bal.type == 'text') {
        bal.type = 'password';
        hideEye.classList.add("fa-eye-slash");
        hideEye.classList.remove("fa-eye");
    } else {
        bal.type = 'text';
        hideEye.classList.add("fa-eye");
        hideEye.classList.remove("fa-eye-slash");
    }
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (this.status == 200) {
        }
    };
    xmlhttp.open("POST", "GETUSERINFO.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("hidebal="+str);
}

function showAddTransaction(a) {
    trform = document.getElementById("addTRForm");
    trlabel = document.getElementById("TRlabel");
    if (a) {
        trlabel.innerHTML = trlabel.innerHTML == 'Close' ? 'Add Transaction' : 'Close';
        if (document.getElementById("WLbutton").checked) {
            document.getElementById("WLbutton").checked = false;
            showAddWish(true);
        } 
    } else trlabel.style.rotate = trlabel.style.rotate == '' ? '45deg' : '';
    trform.style.display = trform.style.display == "none" ? "block" : "none";
    trform.reset();
}

function showAddWish(a) {
    wlform = document.getElementById("addWLForm");
    wllabel = document.getElementById("WLlabel");
    if (a) {
        wllabel.innerHTML = wllabel.innerHTML == 'Close' ? 'Add Wish' : 'Close';
        if (document.getElementById("TRbutton").checked) {
            document.getElementById("TRbutton").checked = false;
            showAddTransaction(true);
        }
    } else wllabel.style.rotate = wllabel.style.rotate == '' ? '45deg' : '';
    wlform.style.display = wlform.style.display == "none" ? "block" : "none";
    wlform.reset();
}

function editwl(a,fid) {
    let form;
    if (!isNaN(editwl.fid) && editwl.fid != fid) {
        form = document.getElementById(editwl.fid);
        if (!form.inert) {
            var p = document.getElementById("price" + editwl.fid);
            var temp = p.placeholder;
            p.placeholder = p.value;
            document.getElementById("target" + editwl.fid).type = "text";
            editwl.a.innerHTML = "Edit";
            form.inert = true;
            form.reset();
            p.value = temp;
        }
    }
    editwl.fid = fid;
    editwl.a = a;
    form = document.getElementById(fid);
    var p = document.getElementById("price" + fid);
    var temp = p.placeholder;
    p.placeholder = p.value;
    t = document.getElementById("target" + fid);
    t.type = t.type == "date" ? "text" : "date";
    a.innerHTML = a.innerHTML == "Edit" ? "Close" : "Edit";
    form.inert = !form.inert;
    form.reset();
    p.value = temp;
}

function filterAll() {
    Array.from(document.querySelectorAll("input[name='filter[]']:not([value='all'])")).forEach(checkbox => checkbox.checked = false);   
}

function sortFilter(form) {
    const otherCheckboxes = Array.from(document.querySelectorAll("input[name='filter[]']:not([value='all'])"));
    const allCheckbox = document.querySelector("input[name='filter[]'][value='all']");
    if (otherCheckboxes.some(checkbox => checkbox.checked)) {
        allCheckbox.checked = false;
    } else allCheckbox.checked = true;
    form.submit();
}

function logout(a) {
    const logoutC = document.getElementById("logoutC");
    logoutC.hidden = !logoutC.hidden;
    a.innerHTML = a.innerHTML == "Logout" ? "Cancel" : "Logout";
}

document.getElementById('dn').onclick = function() {
    this.style.marginTop = "-80px";
};