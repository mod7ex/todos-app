let form = document.querySelector("form"),
  Auth = { m: 0, 0: "login", 1: "signup" };

let validateData = (obj) => {
  let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  let feedBack = {};
  if (Auth.m) {
    if (obj.full_name === "") {
      feedBack.full_name = "Please Enter A valid Full Name";
    }
    if (!regex.test(obj.email.toLowerCase())) {
      feedBack.email = "Please Enter A valid Email";
    }
    if (obj.password.length < 4) {
      feedBack.password = "Password's Length should be more than 4 charachters";
    }
    if (obj.password != obj.password_again) {
      feedBack.password = "Please Reenter The same password";
    }
  } else {
    if (!regex.test(obj.email.toLowerCase())) {
      feedBack.email = "Please Enter A valid Email";
    }
    if (obj.password.length < 4) {
      feedBack.password = "Please Enter a valid Password";
    }
  }
  return feedBack;
};

let fetchAuth = () => {
  fetch(`/user/${Auth[Auth.m]}/template`, {
    method: "GET",
    headers: {
      "Content-Type": "text/html",
    },
  })
    .then((resp) => {
      if (resp.status === 200) {
        return resp.text();
      } else {
        throw new Error("");
      }
    })
    .then((data) => {
      form.innerHTML = data;
    })
    .then(() => {
      document.querySelector("form small a").addEventListener("click", () => {
        Auth.m = Number(!Boolean(Auth.m));
        fetchAuth();
      });
      // **************************************************** show Feed Back
      let showFeedBack = (feedBack, x) => {
        let feedBackArea = document.createElement("div");
        feedBackArea.classList.add("alert", "pt-1", `alert-${x}`);
        feedBackArea.setAttribute("role", "alert");
        feedBackArea.innerHTML = `<ul class="py-0 m-0"></ul>`;
        for (const key in feedBack) {
          let li = document.createElement("li");
          li.innerHTML = feedBack[key];
          feedBackArea.querySelector("ul").appendChild(li);
        }
        form.insertBefore(feedBackArea, document.querySelector("hr"));
        setTimeout(() => {
          feedBackArea.remove();
        }, 6000);
      };
      // ****************************************************
      document.querySelector("form button[type=submit]").addEventListener("click", (e) => {
        e.preventDefault();
        formData = Array();
        if (Auth.m) {
          formData.push({
            full_name: document.querySelector("form input[name=full_name]").value,
            email: document.querySelector("form input[name=email]").value,
            password: document.querySelector("form input[name=password]").value,
            password_again: document.querySelector("form input[name=password_again]").value,
          });
        } else {
          formData.push({
            email: document.querySelector("form input[name=email]").value,
            password: document.querySelector("form input[name=password]").value,
          });
        }
        let feedBack = validateData(formData[0]);
        if (Object.keys(feedBack).length === 0) {
          fetch(`/user/${Auth[Auth.m]}/check`, {
            method: "POST",
            headers: {
              "Content-Type": "text/json",
            },
            body: JSON.stringify(formData[0]),
          })
            .then((resp) => {
              if (resp.ok) {
                return resp.json();
              } else {
                throw new Error("");
              }
            })
            .then((data) => {
              console.log(data);
              if(data.loggedIn){
                window.location.href = "/todo";
              }else{
                showFeedBack({error:data.error}, "danger");
              }
              // if (Object.keys(data).length === 0) {
              //   window.location.href = "/todo";
              // } else {
              //   showFeedBack(data, "danger");
              // }
            })
            .catch((e) => {
              form.innerHTML = "<h1>Some Thing Went Wong</h1>";
              form.style.color = "red";
            });
        } else {
          showFeedBack(feedBack, "danger");
        }
      });
    })
    .catch((e) => {
      form.innerHTML = "<h1>Some Thing Went Wong</h1>";
      form.style.color = "red";
    });
};

fetchAuth();