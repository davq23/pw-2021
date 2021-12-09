document.addEventListener('readystatechange', function (event) {
    switch (document.readyState) {
        case 'interactive':
            document.querySelectorAll('.datepicker-element')
                .forEach(function (datepickerElement) {
                    new Datepicker(datepickerElement, {
                        format: 'yyyy-mm-dd'
                    });
                }
                );

            document.querySelectorAll('.credentials-container')
                .forEach(function (credentialsContainerElement) {
                    credentialsContainerElement.addEventListener('click', function (event) {
                        var action = event.target.getAttribute('action');

                        switch (action) {
                            case 'add-credential':
                                var li = document.createElement('li');
                                li.classList.add('list-group-item', 'd-flex', 'justify-content-between');

                                var input = document.createElement('input');
                                input.classList.add('form-control');
                                input.type = 'text';
                                input.name = this.getAttribute('input-name');

                                var deleteBtn = document.createElement('button');
                                deleteBtn.classList.add('btn', 'btn-danger', 'bi', 'bi-x-lg');
                                deleteBtn.type = 'button';
                                deleteBtn.setAttribute('action', 'delete-credential');

                                li.appendChild(input);
                                li.appendChild(deleteBtn);

                                credentialsContainerElement.querySelector('ul').appendChild(li);
                                break;
                            case 'delete-credential':
                                event.target.parentElement.remove();

                                break;
                        }
                        if (action === 'add-credential') {

                        }
                    });
                });
            break;
        case 'complete':
            break;
    }
});


