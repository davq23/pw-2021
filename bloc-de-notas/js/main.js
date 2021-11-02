
/* global bootstrap */

document.addEventListener('readystatechange', function (event) {
    switch (this.readyState) {
        case 'interactive':
            window.dispatchEvent(new Event('hashchange'));
            break;

        case 'complete':
            loadDirectories();
    }
});

function makeFiledId(filename) {
    return encodeURIComponent(filename.split('/').join('_').split('.txt').join(''));
}

function getFileTabDiv(fileId) {
    return document.getElementById(fileId);
}

function gotoFile(fileId) {
    document.location.hash = '#' + fileId;
}

function newFile(file) {
    if (file.filename.includes('_')) {
        alert('Invalid filename: must not contain "_"');
        return;
    }

    var fileIdEncoded = makeFiledId(file.filename);

    if (getFileTabDiv(fileIdEncoded)) {
        alert('File already opened');
        return;
    }

    var newTabDiv = tabDiv();

    newTabDiv.querySelector('textarea[name="file-contents"]').value = file.fileContents;
    newTabDiv.querySelector('input[name="filename"]').value = file.filename;
    newTabDiv.querySelector('a').href = 'api/download.php?filename=' + encodeURIComponent(file.filename);


    newTabDiv.classList.add('visually-hidden');

    newTabDiv.setAttribute('id', fileIdEncoded);

    document.getElementById('tab-sections').appendChild(newTabDiv);

    var newTabElement = tabElement(fileIdEncoded);

    newTabElement.querySelector('a').innerText = file.filename;

    newTabElement.querySelector('button').setAttribute('data-src', fileIdEncoded);
    document.getElementById('tab-list').appendChild(newTabElement);

    gotoFile(fileIdEncoded);
}

function loadDirectories() {
    var xhr = new XMLHttpRequest();

    xhr.onload = function () {
        switch (this.status) {
            case 200:
                var array = JSON.parse(this.responseText);

                var fragment = document.createDocumentFragment();

                array.forEach(function (element) {
                    if (!element.includes('.txt')) {
                        return;
                    }

                    var li = document.createElement('li');
                    li.innerText = element;
                    li.classList.add('list-group-item', 'btn', 'btn-primary');
                    li.name = 'filepath';
                    li.setAttribute('path', element);

                    fragment.appendChild(li);
                });

                var directoryList = document.getElementById('directory-list');

                directoryList.innerText = '';
                directoryList.appendChild(fragment);
        }
    };

    xhr.open('GET', 'api/list-files.php', true);
    xhr.send();
}

var tabElement = function (filename) {
    var li = document.createElement('li');

    li.classList.add('nav-item', 'd-flex');

    var a = document.createElement('a');
    a.classList.add('nav-link');
    a.innerText = filename;
    a.href = '#' + encodeURIComponent(filename);

    var closeBtn = document.createElement('button');
    closeBtn.name = 'close-file';
    closeBtn.classList.add('btn');
    closeBtn.innerHTML = '&times';

    li.appendChild(a);
    li.appendChild(closeBtn);

    return li;
};

var tabDiv = function () {
    var tabDiv = document.createElement('div');
    tabDiv.classList.add('tab', 'visually-hidden');

    var firstDiv = document.createElement('div');
    firstDiv.classList.add('w-100');

    var filenameInput = document.createElement('input');
    filenameInput.type = 'hidden';
    filenameInput.name = 'filename';

    var textArea = document.createElement('textarea');
    textArea.classList.add('form-control');
    textArea.rows = 10;
    textArea.cols = 50;
    textArea.name = 'file-contents';

    var secondDiv = document.createElement('div');
    secondDiv.classList.add('mt-2');

    var buttonSubmit = document.createElement('button');
    buttonSubmit.classList.add('btn', 'btn-success', 'bi', 'bi-save');
    buttonSubmit.innerText = 'Save file';
    buttonSubmit.type = 'submit';

    var buttonDownload = document.createElement('a');
    buttonDownload.classList.add('btn', 'btn-primary', 'bi', 'bi-hdd');
    buttonDownload.innerText = 'Download file';
    buttonDownload.target = '_blank';

    firstDiv.appendChild(filenameInput);
    firstDiv.appendChild(textArea);
    secondDiv.appendChild(buttonSubmit);
    secondDiv.appendChild(buttonDownload);
    tabDiv.appendChild(firstDiv);
    tabDiv.appendChild(secondDiv);

    return tabDiv;
};

document.getElementById('tab-list').addEventListener('click', function (event) {
    switch (event.target.name) {
        case 'close-file':
            var fileId = event.target.getAttribute('data-src');
            this.querySelector('a[href="#' + fileId + '"]').parentElement.remove();
            document.getElementById(fileId).remove();

            if (window.location.hash === '#' + fileId) {
                window.location.hash = '';
            }
    }
});

document.getElementById('directory-list').addEventListener('click', function (event) {
    switch (event.target.name) {
        case 'filepath':
            var encodedPath = encodeURIComponent(event.target.getAttribute('path'));

            event.target.parentElement.querySelectorAll('.active').forEach(function (directory) {
                directory.classList.remove('active');
            });

            event.target.classList.add('active');

            var xhr = new XMLHttpRequest();

            xhr.onload = function () {
                switch (this.status) {
                    case 200:
                        var fileJSON = JSON.parse(this.responseText);

                        newFile(fileJSON);

                }
            };

            xhr.open('GET', 'api/get_file.php?filename=' + encodedPath, true);
            xhr.send();
            break;
    }
});

document.getElementById('tab-sections').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();

    this.querySelectorAll('input,textarea,button').forEach(function (input) {
        input.disabled = true;
    });

    var form = this;

    xhr.onload = function () {
        form.querySelectorAll('input,textarea,button').forEach(function (input) {
            input.disabled = false;
        });

        switch (xhr.status) {
            case 200:
                loadDirectories();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createFileModal')).hide();
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.getElementById('create-file-form').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    if (formData.get('filename').includes('_')) {
        alert('Filename cannot contain "_"');
        return;
    }

    var xhr = new XMLHttpRequest();

    if (getFileTabDiv(makeFiledId(formData.get('filename')))) {
        alert('Close file ' + formData.get('filename') + 'before deleting it');
        return;
    }

    this.querySelector('input,textarea', function (input) {
        input.disabled = true;
    });

    var errorTag = this.querySelector('strong');
    errorTag.innerText = '';

    var form = this;

    xhr.onload = function () {
        form.querySelector('input,textarea', function (input) {
            input.disabled = false;
        });

        switch (xhr.status) {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createFileModal')).hide();
                loadDirectories();
                break;

            default:
                errorTag.innerText = this.responseText;
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.getElementById('delete-file-form').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    if (formData.get('filename').includes('_')) {
        alert('Filename cannot contain "_"');
        return;
    }

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input) {
        input.disabled = true;
    });

    if (document.getElementById(formData.get('filename'))) {
        alert('Close the file before deleting');
        return;
    }

    var errorTag = this.querySelector('strong');
    errorTag.innerText = '';

    var form = this;

    xhr.onload = function () {
        form.querySelector('input,textarea', function (input) {
            input.disabled = false;
        });

        switch (xhr.status) {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteFileModal')).hide();
                loadDirectories();
                break;

            default:
                errorTag.innerText = this.responseText;
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});


document.getElementById('create-dir-form').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    if (formData.get('dirname').includes('_')) {
        alert('Filename cannot contain "_"');
        return;
    }

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input) {
        input.disabled = true;
    });

    var errorTag = this.querySelector('strong');

    var form = this;

    xhr.onload = function () {
        form.querySelector('input,textarea', function (input) {
            input.disabled = false;
        });

        switch (xhr.status) {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createDirModal')).hide();
                loadDirectories();
                break;
            default:
                errorTag.innerText = this.responseText;
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.getElementById('delete-dir-form').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);

    if (formData.get('dirname').includes('_')) {
        alert('Filename cannot contain "_"');
        return;
    }

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input) {
        input.disabled = true;
    });

    var errorTag = this.querySelector('strong');

    var form = this;

    xhr.onload = function () {
        form.querySelector('input,textarea', function (input) {
            input.disabled = false;
        });

        switch (xhr.status) {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteDirModal')).hide();
                loadDirectories();
                break;
            default:
                errorTag.innerText = this.responseText;
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.querySelectorAll('.modal').forEach(function (modal) {
    modal.addEventListener('hidden.bs.modal', function () {
        this.querySelectorAll('input').forEach(function (input) {
            input.value = '';
        });

        this.querySelectorAll('strong').forEach(function (strong) {
            strong.innerText = '';
        });
    });
});

window.addEventListener('hashchange', function (event) {
    event.preventDefault();

    var hash = window.location.hash;

    var tabList = document.getElementById('tab-list');

    document.querySelectorAll('.tab').forEach(function (tab) {
        tab.classList.add('visually-hidden');

        tab.querySelectorAll('textarea,input,button').forEach(function (input) {
            input.disabled = true;
        });
    });

    tabList.querySelectorAll('li > a.active').forEach(function (li) {
        li.classList.remove('active');
    });

    var li = tabList.querySelector('li > a[href="' + hash + '"]');

    if (!li) {
        window.location.hash = '';
        return;
    }

    li.classList.add('active');

    var tab = document.body.querySelector(hash);

    tab.querySelectorAll('textarea,input,button').forEach(function (input) {
        input.disabled = false;
    });

    tab.classList.remove('visually-hidden');
});


