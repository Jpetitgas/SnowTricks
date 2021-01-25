window.onload=()=>{
  const plus = document.getElementById("plus");
  
plus.addEventListener("click", ()=>{
  const page = document.getElementById("page").innerText;
  const Url=new URL(window.location.href);
  document.getElementById("loader").setAttribute("style", "display:block");
  fetch(Url.pathname +"?page="+ page +"&ajax=1", {
    headers: {
      "X-Requested-with": "XMLHttpRequest"
    }
  }).then(response=>
    response.json()
  ).then(data=>{
    document.querySelector("#content").innerHTML = data.contenu;
    document.querySelector("#page").innerHTML = data.page;
    document.getElementById("loader").setAttribute("style", "display:none");
  }).catch(e=>alert(e))
});

  document.getElementById("togg1").addEventListener("click", () => {
    document.getElementById("content").scrollIntoView({ behavior: "smooth", block: "start", inline: "start"});
 
})
  document.getElementById("togg2").addEventListener("click", () => {
    document.getElementById("content").scrollIntoView({ behavior: "smooth", block: "start", inline: "start" });
  })
}
