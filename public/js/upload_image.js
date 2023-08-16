const sampul = document.querySelector('#image');
let dropZone = document.getElementById('dropzone');

function showDropZone() {
    dropZone.style.display = "block";
}

function hideDropZone() {
    dropZone.style.display = "none";
}

function allowDrag(e) {
    if (true) {
        e.preventDefault();
    }
}

function handleDrop(e) {
    hideDropZone();
    sampul.files = e.dataTransfer.files;
    previewImg()
    e.preventDefault();
}

window.addEventListener('dragenter', function (e) {
    showDropZone();
});

dropZone.addEventListener('dragenter', allowDrag);
dropZone.addEventListener('dragover', allowDrag);

dropZone.addEventListener('dragleave', function (e) {
    hideDropZone();
});

dropZone.addEventListener('drop', handleDrop);

function previewImg(id = null) {
    if (id == null) {
        const imgPreview = document.querySelector('.img-preview');

        const fileSampul = new FileReader();
        fileSampul.readAsDataURL(sampul.files[0]);
        fileSampul.onload = function (e) {
            imgPreview.src = e.target.result;
        }
    } else {
        const sampul = document.querySelector('#custom-image' + id);
        if (id == 0) {
            const imgPreview = document.querySelector('.img-preview');
            const fileSampul = new FileReader();
            fileSampul.readAsDataURL(sampul.files[0]);
            fileSampul.onload = function (e) {
                imgPreview.src = e.target.result;
            }
        }
        const imgPreview = document.querySelector('#img-preview' + id);
        if (document.querySelector('#image-upload-wrap' + id)) {
            document.querySelector('#image-upload-wrap' + id).classList.add('d-none');
            document.querySelector('#file-upload-content' + id).classList.remove('d-none');
            imgPreview.classList.remove('d-none');
        }

        const fileSampul = new FileReader();
        fileSampul.readAsDataURL(sampul.files[0]);
        fileSampul.onload = function (e) {
            imgPreview.src = e.target.result;
        }
    }
}
