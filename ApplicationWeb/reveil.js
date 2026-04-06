const apiUrls = [
    "https://render-10dx.onrender.com/", 
    "https://api-projetsdd4.onrender.com/"
];

if (!sessionStorage.getItem('apis_awakened')) {
    console.log("Premier chargement : Réveil des APIs en cours...");
    
    apiUrls.forEach(url => {
        fetch(url, { mode: 'no-cors' })
            .then(() => {
                console.log("Signal envoyé à :", url);
                sessionStorage.setItem('apis_awakened', 'true');
            })
            .catch(err => console.log("L'API est en cours de démarrage..."));
    });
}