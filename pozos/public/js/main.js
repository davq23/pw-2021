/* global AutoNumeric */

document.addEventListener('readystatechange', function () {
    switch (document.readyState) {
        case 'interactive':
            document.querySelectorAll('.datepicker-element')
                .forEach(function (datepickerElement) {
                    new Datepicker(datepickerElement, {
                        format: 'yyyy-mm-dd'
                    });
                }
                );
            document.querySelectorAll('.autonumeric')
                .forEach(function (autoNumericElement) {
                    new AutoNumeric(autoNumericElement, {
                        digitGroupSeparator: ".",
                        decimalCharacter: ",",
                        suffixText: autoNumericElement.getAttribute('suffix'),
                        unformatOnSubmit: true
                    });
                }
                );
            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('reset', function (event) {
                    this.querySelectorAll('.autonumeric')
                        .forEach(function (autoNumericElement) {
                            AutoNumeric.getAutoNumericElement(autoNumericElement).clear();
                        });
                });
            });


            document.querySelectorAll('form.ajax-form')
                .forEach(function (ajaxForms) {
                    ajaxForms.addEventListener('submit', function (event) {
                        event.preventDefault();
                        var formData = new FormData(this);
                        var object = formDataToObject(formData);
                        var form = this;
                        var alert = form.querySelector('.alert');
                        var alertStrong = alert.querySelector('strong');
                        var alertIcon = alert.querySelector('i');
                        alert.classList.add('invisible');
                        var xhr = new XMLHttpRequest();
                        xhr.onload = function () {
                            var data = JSON.parse(this.responseText);
                            switch (this.status) {
                                case 200:
                                    alertStrong.innerText = data['message'];
                                    alert.classList.remove('alert-danger');
                                    alert.classList.add('alert-success');
                                    alertIcon.classList.remove('bi-exclamation-triangle-fill');
                                    alertIcon.classList.add('bi-check-circle-fill');
                                    form.reset();
                                    break;
                                default:
                                    alertStrong.innerText = data['message'];
                                    alert.classList.add('alert-success');
                                    alert.classList.add('alert-danger');
                                    alertIcon.classList.add('bi-exclamation-triangle-fill');
                                    alertIcon.classList.remove('bi-exclamation-triangle-fill');
                                    break;
                            }

                            alert.classList.remove('invisible');
                        };
                        xhr.open(this.method, this.action);
                        xhr.send(JSON.stringify(object));
                    });
                }
                );
            break;
        case 'complete':
            document.querySelectorAll('.modal')
                .forEach(function (modal) {
                    modal.addEventListener('hidden.bs.modal', function () {
                        var alert = this.querySelector('.alert');
                        if (alert)
                            alert.classList.add('invisible');
                        this.querySelectorAll('form').forEach(function (form) {
                            form.reset();
                        });
                    });
                });
            if (document.getElementById('modalAddMeasurement')) {
                document.getElementById('modalAddMeasurement')
                    .addEventListener('show.bs.modal', function (event) {
                        var oilWell = JSON.parse(event.relatedTarget.getAttribute('data-bs-src'));
                        this.querySelector('input[name="oil_well_id"]').value = oilWell['id'];
                        this.querySelector('[name="name"]').innerText = oilWell['name'];
                    });
            }

            if (document.getElementById('chart-panel')) {
                var chart = new Chart(document.getElementById('chart-panel').getContext('2d'), {
                    type: 'line',
                    data: {
                        datasets: [
                            {
                                backgroundColor: "red",
                                labels: [],
                                data: [],
                                pointColor: "red",
                                fill: false,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1
                            }
                        ]
                    }

                });
                document.getElementById('modalChart')
                    .addEventListener('show.bs.modal', function (event) {
                        var oilWell = JSON.parse(event.relatedTarget.getAttribute('data-bs-src'));
                        var url = this.getAttribute('url');

                        var xhr = new XMLHttpRequest();

                        xhr.onload = function () {
                            var result = JSON.parse(this.responseText);
                            switch (this.status) {
                                case 200:
                                    chart.data.datasets[0].label = oilWell['name'];
                                    chart.data.datasets[0].data = result.data.map(function (datum) {
                                        return {value: datum.value, time: new Date(datum.time)};
                                    });
                                    chart.update();
                                    break;
                            }
                        };

                        xhr.open('GET', url + '?oil_well=' + oilWell.id);
                        xhr.send();
                    });
            }
            break;
    }
});
function formDataToObject(formData) {
    var object = {};
    formData.forEach(function (value, key) {
        object[key] = value;
    });
    return object;
}


