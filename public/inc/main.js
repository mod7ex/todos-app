let togglerTarget = document.getElementById("togglerTarget"),
  toggler = document.getElementById("toggler"),
  path = window.location.pathname.substring(1);

toggler.addEventListener("click", () => {
  togglerTarget.classList.toggle("hidden");
});

if (path == "user") {
  document.getElementById("editBtn").addEventListener("click", () => {
    document.querySelector("form > fieldset").toggleAttribute("disabled");
    document.querySelector("form > fieldset .passwd").classList.toggle("hidden");
  });

  document.querySelector("#profile a").addEventListener("click", (e) => {
    e.currentTarget.nextElementSibling.click();
  });
}

if (path == "todo" || path == "") {
  let btn = document.createElement("button");
  btn.style = "border-radius:50%;";
  btn.classList.add("btn", "btn-outline-primary", "mr-auto");
  btn.innerHTML = `<i class="fas fa-plus"></i>`;
  document.querySelector("header").appendChild(btn);
  // document.querySelector("header").innerHTML += `<Button style="border-radius:50%;" class="btn btn-outline-primary mr-auto">
  //                                                <i class="fas fa-plus"></i>
  //                                                </Button>`;

  // let script = document.createElement("script");
  // script.src = "inc/todo.js";
  // document.querySelector("body").appendChild(script);
}
