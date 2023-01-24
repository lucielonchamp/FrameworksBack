/* ******************** VÉRIFICATION FORMULAIRE ******************** */

    var champsFormulaire =[];
    champsFormulaire.push(document.querySelector('#produit_title'));
    champsFormulaire.push(document.querySelector('#produit_city'));
    champsFormulaire.push(document.querySelector('#produit_postalCode'));
    champsFormulaire.push(document.querySelector('#produit_price'));
    champsFormulaire.push(document.querySelector('#produit_description'));

    function verifierFormulaire() {
        champsFormulaire.forEach(element => {
            if (element.value === '') {
                element.classList.add('bg-danger', 'text-light');
                element.placeholder = 'Ce champ ne peut être vide';
            } else {
                element.classList.remove('bg-danger', 'text-light');
            }
        })
    }
