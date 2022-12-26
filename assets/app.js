/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
console.log('chargement app.js is ok !');
// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const axios = require('axios');
document.querySelectorAll('a.likes').forEach(function(link){
    link.addEventListener('click', onClickLikes);
})
function onClickLikes(event) {
    event.preventDefault();
    const url = this.href;
    const icon = this.querySelector('i');
    axios.post(url).then(function(response){
        if (icon.classList.contains('fa-regular')) {
            console.log('contient fa-regular');
            icon.classList.replace('fa-regular', 'fa-solid');
        } else if (icon.classList.contains('fa-solid')){
            console.log('contient fa-solid');
            icon.classList.replace('fa-solid', 'fa-regular');
        }
    }).catch(function (error) {
        console.log(error);
    })
}
document.addEventListener('DOMContentLoaded', function () {
    var modeSwitch = document.querySelector('.mode-switch');

/*    modeSwitch.addEventListener('click', function () {
        document.documentElement.classList.toggle('dark');
        modeSwitch.classList.toggle('active');
    });*/

    var listView = document.querySelector('.list-view');
    var gridView = document.querySelector('.grid-view');
    var projectsList = document.querySelector('.project-boxes');

    listView.addEventListener('click', function () {
        gridView.classList.remove('active');
        listView.classList.add('active');
        projectsList.classList.remove('jsGridView');
        projectsList.classList.add('jsListView');
    });

    gridView.addEventListener('click', function () {
        gridView.classList.add('active');
        listView.classList.remove('active');
        projectsList.classList.remove('jsListView');
        projectsList.classList.add('jsGridView');
    });

    document.querySelector('.messages-btn').addEventListener('click', function () {
        document.querySelector('.messages-section').classList.add('show');
    });

/*    document.querySelector('.messages-close').addEventListener('click', function() {
        document.querySelector('.messages-section').classList.remove('show');
    });*/
});
