const submit_btn = document.getElementById("submit");
const user_select = document.getElementById("user");
const data_div = document.getElementById("data");
const baseUrl = window.location.origin; // Получаем полный URL сайта

submit_btn.onclick = function (e) {
  e.preventDefault();
  //data_div.style.display = "none";

  const user_id = user_select.value;
  if (!user_id) return;
  fetch(`${baseUrl}/data?user=${user_id}`)
    //.then((response) => response.json())
    .then((response) => response.text()) // Изменили json() на text()
    /*.then((text) => {
      console.log("Ответ сервера:", text); // Посмотрим в консоли
      return JSON.parse(text);
    })*/
    /*.then((data) => {
      data_div.innerHTML = `${data}`;
    })*/
    .then((text) => {
      data_div.innerHTML = `${text}`;
    })
    .catch((error) => {
      console.error("Ошибка загрузки данных:", error);
      data_div.innerHTML = "Ошибка загрузки данных";
    });

  // TODO: implement
  // alert("Not implemented");
  data_div.style.display = "block";
};
