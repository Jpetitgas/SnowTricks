window.onload=()=>{
  const plus = document.getElementById("plus");
  
plus.addEventListener("click", ()=>{
  const page = document.getElementById("page").innerText;
  const Url=new URL(window.location.href);
  
  fetch(Url.pathname +"?page="+ page +"&ajax=1", {
    headers: {
      "X-Requested-with": "XMLHttpRequest"
    }
  }).then(response=>
    response.json()
  ).then(data=>{
    document.querySelector("#content").innerHTML = data.contenu;
    document.querySelector("#page").innerHTML = data.page;
    
  }).catch(e=>alert(e))
});

  document.getElementById("togg1").addEventListener("click", () => {
  document.getElementById("togg2").scrollIntoView({behavior: "smooth", block: "start", inline: "center"});
 
})
  document.getElementById("togg2").addEventListener("click", () => {
    document.getElementById("image").scrollIntoView({ behavior: "smooth", block: "start", inline: "center" });
  })
}
