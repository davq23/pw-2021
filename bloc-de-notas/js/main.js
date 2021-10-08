
/* global bootstrap */

document.addEventListener('readystatechange', function (event)
{
    switch (this.readyState)
    {
        case 'interactive':
            window.dispatchEvent(new Event('hashchange'));
            break;

        case 'complete':
            loadDirectories();
    }
});

function newFile(file)
{
    var fileId = file.filename.replace('.txt', '');
    fileId = fileId.replace('/', '0000');
    var fileIdEncoded = encodeURIComponent(fileId);

    if (document.getElementById(fileIdEncoded))
    {
        alert('File already opened');
        return;
    }

    var newTabDiv = tabDiv();

    newTabDiv.querySelector('textarea[name="file-contents"]').value = file.fileContents;
    newTabDiv.querySelector('input[name="filename"]').value = file.filename;

    newTabDiv.classList.add('visually-hidden');


    newTabDiv.setAttribute('id', fileIdEncoded);

    document.getElementById('tab-sections').appendChild(newTabDiv);

    var newTabElement = tabElement(fileId);

    newTabElement.querySelector('a').innerText = file.filename;

    newTabElement.querySelector('button').setAttribute('data-src', fileIdEncoded);
    document.getElementById('tab-list').appendChild(newTabElement);

    document.location.hash = '#' + fileId;
}

function loadDirectories()
{
    var xhr = new XMLHttpRequest();

    xhr.onload = function ()
    {
        switch (this.status)
        {
            case 200:
                var array = JSON.parse(this.responseText);

                var fragment = document.createDocumentFragment();

                array.forEach(function (element)
                {
                    if (!element.includes('.txt'))
                    {
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

    xhr.open('GET', 'api/list-directories.php', true);
    xhr.send();
}

var tabElement = function (filename)
{
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

var tabDiv = function ()
{
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
    buttonSubmit.classList.add('btn', 'btn-primary');
    buttonSubmit.innerText = 'Save file';
    buttonSubmit.type = 'submit';

    firstDiv.appendChild(filenameInput);
    firstDiv.appendChild(textArea);
    secondDiv.appendChild(buttonSubmit);
    tabDiv.appendChild(firstDiv);
    tabDiv.appendChild(secondDiv);

    return tabDiv;
};

document.getElementById('tab-list').addEventListener('click', function (event)
{
    switch (event.target.name)
    {
        case 'close-file':
            var fileId = event.target.getAttribute('data-src');
            this.querySelector('a[href="#' + fileId + '"]').parentElement.remove();
            document.getElementById(fileId).remove();

            if (window.location.hash = '#' + fileId)
            {
                window.location.hash = '';
            }
    }
});

document.getElementById('directory-list').addEventListener('click', function (event)
{
    switch (event.target.name)
    {
        case 'filepath':
            var encodedPath = encodeURIComponent(event.target.getAttribute('path'));

            event.target.parentElement.querySelectorAll('.active').forEach(function (directory) {
                directory.classList.remove('active');
            });

            event.target.classList.add('active');

            var xhr = new XMLHttpRequest();

            xhr.onload = function ()
            {
                switch (this.status)
                {
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

document.getElementById('tab-sections').addEventListener('submit', function (event)
{
    event.preventDefault();

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input)
    {
        input.disabled = true;
    });

    var form = this;

    xhr.onload = function ()
    {
        form.querySelector('input,textarea', function (input)
        {
            input.disabled = false;
        });

        switch (xhr.status)
        {
            case 200:
                loadDirectories();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createFileModal')).hide();
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.getElementById('create-file-form').addEventListener('submit', function (event)
{
    event.preventDefault();

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input)
    {
        input.disabled = true;
    });

    var form = this;

    xhr.onload = function ()
    {
        form.querySelector('input,textarea', function (input)
        {
            input.disabled = false;
        });

        switch (xhr.status)
        {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createFileModal')).hide();
                loadDirectories();
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});


document.getElementById('create-dir-form').addEventListener('submit', function (event)
{
    event.preventDefault();

    var formData = new FormData(this);

    var xhr = new XMLHttpRequest();

    this.querySelector('input,textarea', function (input)
    {
        input.disabled = true;
    });

    var form = this;

    xhr.onload = function ()
    {
        form.querySelector('input,textarea', function (input)
        {
            input.disabled = false;
        });

        switch (xhr.status)
        {
            case 200:
                bootstrap.Modal.getOrCreateInstance(document.getElementById('createDirModal')).hide();
                loadDirectories();
                break;
        }
    };

    xhr.open('POST', this.getAttribute('action'), true);

    xhr.send(formData);
});

document.querySelectorAll('.modal').forEach(function (modal)
{
    modal.addEventListener('hidden.bs.modal', function ()
    {
        this.querySelectorAll('input').forEach(function(input) 
        {
            input.value = '';
        });
    });
});

window.addEventListener('hashchange', function (event)
{
    event.preventDefault();

    var hash = window.location.hash;

    var tabList = document.getElementById('tab-list');

    document.querySelectorAll('.tab').forEach(function (tab)
    {
        tab.classList.add('visually-hidden');

        tab.querySelectorAll('textarea,input,button').forEach(function (input) {
            input.disabled = true;
        });
    });

    tabList.querySelectorAll('li > a.active').forEach(function (li)
    {
        li.classList.remove('active');
    });

    var li = tabList.querySelector('li > a[href="' + hash + '"]');

    if (!li)
    {
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


