const btnApi = document.querySelector('.btn');
btnApi.addEventListener('click', ()=>{
    getDataApi();
});

function getDataApi() {
    tsx = new XMLHttpRequest();
    console.log(tsx);

    tsx.open('GET', 'https://reqres.in/api/users?page=2');
    tsx.onreadystatechange = function(){
        if (tsx.readyState === 4 && tsx.status === 200) {
            const tsxText = document.createElement('p');
            tsxText.textContent = tsx.responseText;
            document.body.appendChild(tsxText);
            console.log(tsx.responseText);

            const tsxApi = JSON.parse(tsx.responseText);
            for (let index = 0; index < tsxApi.length; index++) {
                const element = tsxApi['data'][index];
                document.body.append(element.email);
                console.log(element.email);
            }
        }else{
            console.log({state: tsx.readyState, status: tsx.status, text: tsx.statusText});
        }
        tsx.send();
    }
    
}