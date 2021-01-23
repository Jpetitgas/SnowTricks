window.onload = () => {
  const plus = document.getElementById("plus");

  plus.addEventListener("click", () => {
    const page = document.getElementById("page").innerText;
    const Url = new URL(window.location.href);
    document.getElementById("loader").setAttribute("style", "display:block");
    fetch(Url.pathname + "?page=" + page + "&ajax=1", {
      headers: {
        "X-Requested-with": "XMLHttpRequest"
      }
    }).then(response =>
      response.json()
    ).then(data => {
      document.querySelector("#commentaire").innerHTML = data.contenu;
      document.querySelector("#page").innerHTML = data.page;
      document.getElementById("loader").setAttribute("style", "display:none");
    }).catch(e => alert(e))
  });
  document.getElementById("togg2").addEventListener("click", () => {
    document.getElementById("image").scrollIntoView({ behavior: "smooth", block: "start", inline: "center" });
  })
  document.getElementById("under").addEventListener("click", () => {
    document.getElementById("medias").style.display = "block"; 
  })
  document.getElementById("close").addEventListener("click", () => {
    document.getElementById("medias").style.display = "none"; 
  })
}