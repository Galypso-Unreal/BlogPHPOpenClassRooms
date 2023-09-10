document.addEventListener("DOMContentLoaded", () => {
  /* get DOM element of the class : .header-account  and set in the variable : account */
  let account = document.querySelector(".header-account ");

  /* Adds an enema when the mouse is clicked on the following class: .floating-window-user
    If the DOM element is present, two conditions must be done: 

    1 - if the close class is present on the element, then add the open class to display a pop-up to the user

    2- delete the user's pop-up window if the open class is present
  */
  account.addEventListener("click", () => {
    let floatingwindow = account.querySelector(".floating-window-user");
    if (floatingwindow) {
      if (floatingwindow.classList.contains("close")) {
        floatingwindow.classList.remove("close");
        floatingwindow.classList.add("open");
      } else {
        floatingwindow.classList.add("close");
        floatingwindow.classList.remove("open");
      }
    }
  });
});
