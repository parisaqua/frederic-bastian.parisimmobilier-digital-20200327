/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import Places from 'places.js';
import Map from './modules/map';
import $ from 'jquery';
import 'select2';  
import 'slick-carousel';
import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css';                     

//initialisation de la carte
Map.init();

//carousel des images des biens
$('[data-slider]').slick({
  dots: true,
  arrows: true,
  infinite: true,
  speed: 500,
  fade: true,
  autoplay: true,
  autoplaySpeed: 2000,
  cssEase: 'linear'
})

//mise en place de l'autocomplétion des adresses
let inputAddress = document.querySelector('#property_address')
let adminInputAddress = document.querySelector('#admin_property_address')
if (inputAddress !== null) {
  let place = Places({
    container: inputAddress
  })
  place.on('change', e => {
    document.querySelector('#property_city').value = e.suggestion.city
    document.querySelector('#property_postalCode').value = e.suggestion.postcode
    document.querySelector('#property_lat').value = e.suggestion.latlng.lat
    document.querySelector('#property_lng').value = e.suggestion.latlng.lng
  })
}
if (adminInputAddress !== null) {
  let place = Places({
    container: adminInputAddress
  })
  place.on('change', e => {
    document.querySelector('#admin_property_city').value = e.suggestion.city
    document.querySelector('#admin_property_postalCode').value = e.suggestion.postcode
    document.querySelector('#admin_property_lat').value = e.suggestion.latlng.lat
    document.querySelector('#admin_property_lng').value = e.suggestion.latlng.lng
  })
}

//Mise en place de la recherche
let searchAddress = document.querySelector('#search_address')
if (searchAddress !== null) {
  let place = Places({
    container: searchAddress
  })
  place.on('change', e => {
    document.querySelector('#lat').value = e.suggestion.latlng.lat
    document.querySelector('#lng').value = e.suggestion.latlng.lng
  })
}

// styler les selects de choix multiples avec select 2
$(() => {
  $('select').select2();
});

// formulaire de contact Affichage et Annulation
let $contactButton = $('#contactButton')
$contactButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideDown();
  $('#annulButton').slideDown();
  $contactButton.slideUp();
})
let $annulButton = $ ('#annulButton')
$annulButton.click(e => {
  e.preventDefault();
  $('#contactForm').slideUp();
  $('#contactButton').slideDown();
  
  $annulButton.slideUp();
})


// Suppression des éléments
document.querySelectorAll('[data-delete]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault()
    fetch(a.getAttribute('href'), {
      method: 'DELETE',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({'_token': a.dataset.token})
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
          a.parentNode.parentNode.removeChild(a.parentNode)
        } else {
          alert(data.error)
        }
      })
      .catch(e => alert(e))
  })
})

//Image preview onload inputAddress

var preview = document.getElementById('avatar')
var file_input = document.getElementById('profile_imageFile_file')
window.previewFile  = function ()
{
    let file = file_input.files[0]
    let reader = new FileReader()
    reader.addEventListener('load', function (event)
    {
      preview.src = reader.result
    }, false)

    if (file)
    {
      document.getElementById("currentAvatar").style.display = "none"
      document.getElementById("previewAvatar").style.display = "block"
      reader.readAsDataURL(file)
    }
}


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Connexion ok, Fred !');
