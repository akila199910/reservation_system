
$(document).ready(function () {
    document.getElementById('file-label').addEventListener('click', function () {
        document.getElementById('image').click();
    });

    document.getElementById('image').addEventListener('change', function () {
        var fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.querySelector('.upload-path').value = fileName;
    });

    //Normal Image for elements
    document.getElementById('file-label-normal').addEventListener('click', function () {
        document.getElementById('image_normal').click();
    });

    document.getElementById('image_normal').addEventListener('change', function () {
        var fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.querySelector('.upload-path-normal').value = fileName;
    });
});

