import { getData, sendData } from "./cases/fetch.js";
import { validateEmail, validateNombre } from "./cases/regex.js";
import { validateData } from "./cases/utils.js";

// Variable global para guardar los datos de los usuarios y poder re utilizarlos en caso de ser necesario.
let getUsers;
let getAreas;
let getRoles;
const tbodyEmpleados = document.querySelector("#tbodyEmpleados");
const btnCrearEmpleado = document.querySelector("#btnCrearEmpleado");
const closeBtnEditar = document.querySelector("#closeBtnEditar");
const closeBtnCrear = document.querySelector("#closeBtnCrear");
const modalCrear = document.querySelector("#modalCrear");
const modalEditar = document.querySelector("#modalEditar");
const modalFormEdit = document.querySelector('#formEditar');
// const modalFormCrear = document.querySelector('#modalCrear #formCrear');
const modalFormCrear = document.querySelector('#formCrear');

// Selectores para renderizar los roles según el modal
const rolesEditar = document.querySelector('#modalEditar .roles');
const areasCrear = document.querySelector('#modalCrear .roles');
const rolesCrear = document.querySelector('#modalCrear .roles');
const areasEditar = document.querySelector('#modalEditar .roles');

console.log('hello world');


// Checkbox de los roles del modal de edición.
// Objeto del formulario editar para validar que elementos son obligatorios y cuales no.
const inputsFormEdit = {
  nombre: "Nombre Completo",
  email: "Correo Electrónico",
  genero: "Sexo",
  area_id: "Área",
  descripcion: "Descripción",
  boletin: "Boletín Informativo",
  roles: "Roles"
};

const renderEmpleados = (getUsers = {}) => {
  if (!getUsers) {
    return;
  }
  const data = getUsers.data;
  const fragment = document.createDocumentFragment();
  tbodyEmpleados.innerHTML = "";
  data.forEach((user) => {
    let btnModificar = document.createElement("button");
    btnModificar.setAttribute("class", "btnModificar");
    btnModificar.setAttribute("data-id", user.idEmpleado);
    let btnEliminar = document.createElement("button");
    btnEliminar.setAttribute("idEmpleado", `${user.idEmpleado}`);
    btnEliminar.innerText = "Eliminar";
    btnModificar.innerText = "Modificar";
    let tr = document.createElement("tr");

    let tdNombre = document.createElement("td");
    let tdEmail = document.createElement("td");
    let tdSexo = document.createElement("td");
    let tdArea = document.createElement("td");
    let tdBoletin = document.createElement("td");
    let tdModificar = document.createElement("td");
    let tdEliminar = document.createElement("td");

    tdNombre.innerText = user.nombreEmpleado;
    tdEmail.innerText = user.email;
    tdSexo.innerText = user.genero;
    tdArea.innerText = user.nombreArea;
    tdBoletin.innerText = user.boletin === 1 ? "Si" : "No";
    tdModificar.append(btnModificar);
    tdEliminar.append(btnEliminar);
    tr.append(
      tdNombre,
      tdEmail,
      tdSexo,
      tdArea,
      tdBoletin,
      tdModificar,
      tdEliminar
    );

    fragment.append(tr);
  });

  tbodyEmpleados.append(fragment);
};
const renderAreas = (getAreas = {}, selector) => {
  if (!getAreas) {
    return;
  }
  const inputAreaId = document.querySelector(selector);
  const data = getAreas.data;
  const message = getAreas.message;
  const frgment = document.createDocumentFragment();
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.innerText = "-- Selecciona un área --";
  defaultOption.disabled = true;
  defaultOption.selected = true;
  frgment.appendChild(defaultOption);
  inputAreaId.innerHTML = "";
  data.forEach((ar) => {
    let option = document.createElement("option");
    option.setAttribute("class", "areaOption");
    option.value = ar.idArea;
    option.innerText = ar.nombreArea;
    frgment.appendChild(option);
  });
  inputAreaId.appendChild(frgment);
};
const renderRoles = (getRoles = {}, selector) => {
  if (!getRoles) {
    return;
  }
  const data = getRoles.data;
  const frgment = document.createDocumentFragment();
  const roles = document.querySelector(selector);
  roles.innerHTML = "";
  data.forEach((rl) => {
    let div = document.createElement("div");
    let checkbox = document.createElement("input");
    checkbox.setAttribute("type", "checkbox");
    checkbox.value = rl.idRol;
    checkbox.name = "idRol";
    checkbox.setAttribute('class', 'checkboxClass');
    let span = document.createElement("span");
    span.innerText = rl.nombreRol;
    div.appendChild(checkbox);
    div.appendChild(span);
    frgment.append(div);
  });

  roles.appendChild(frgment);
};

let rolesAgregados = [];
const addRol = (idRol, isAdd = false) => {
  if (!idRol) {
    return;
  }
  if (isAdd) {
    // Evitar duplicado
    if (!rolesAgregados.includes(idRol)) {
      rolesAgregados.push(idRol);

    }
  } else {
    // Aplicar filter.
    rolesAgregados = rolesAgregados.filter((rl) => idRol != rl);
  }


}

// Abrir modal
btnCrearEmpleado.addEventListener("click", (f) => {
  f.stopPropagation();
  f.preventDefault();
  modalCrear.style.display = "flex";
  renderRoles(getRoles, "#modalCrear .roles");
  renderAreas(getAreas, "#modalCrear #inputAreaId");


  const checkBoxCrear = document.querySelectorAll('#modalCrear .checkboxClass');
  console.log(checkBoxCrear);
  // Evento del checkbox del formulario crear
  checkBoxCrear.forEach((e) => {
    e.addEventListener('change', (f) => {
      f.stopPropagation();
      console.log(f.target);
      if (f.target.checked) {
        addRol(f.target.value, true);
      } else {
        addRol(f.target.value, false);
      }
    });
  });


});




closeBtnCrear.addEventListener("click", (e) => {
  e.stopPropagation();
  modalCrear.style.display = "none";
  rolesAgregados.length = 0;
});



closeBtnEditar.addEventListener("click", (e) => {
  e.stopPropagation();
  modalEditar.style.display = "none";
  //   Limpio el arreglo cada vez que se cierre el modal.
  rolesAgregados.length = 0;
});

tbodyEmpleados.addEventListener("click", (event) => {
  if (
    event.target.tagName === "BUTTON" &&
    event.target.classList.contains("btnModificar")
  ) {
    renderRoles(getRoles, "#modalEditar .roles");
    renderAreas(getAreas, "#modalEditar #inputAreaId");
    let idUsuario = parseInt(event.target.getAttribute("data-id"));
    let dataUsers = getUsers.data;
    let dataEmpleado = dataUsers.find((user) => user.idEmpleado === idUsuario);
    // Inputs del formulario de edición.
    const inputNombre = document.querySelector("#modalEditar #nombreEditar");
    const inputEmail = document.querySelector("#modalEditar #emailEditar");
    const idEmpleado = document.querySelector('#modalEditar #idEmpleado');
    const inputGenero = document
      .querySelectorAll('#modalEditar input[name="genero"]')
      .forEach((g) => {
        if (g.value === dataEmpleado.genero) g.checked = true;
      });
    const inputDescripcion = document.querySelector(
      "#modalEditar #inputDescripcion"
    );
    const checkboxRoles = document
      .querySelectorAll('#modalEditar .roles div input[type="checkbox"]')
      .forEach((r) => {
        if (parseInt(r.value) === parseInt(dataEmpleado.idRol))
          r.checked = true;
      });
    const inputAreaId = document
      .querySelectorAll("#modalEditar .areaOption")
      .forEach((input) => {
        if (parseInt(input.value) === parseInt(dataEmpleado.idArea)) {
          input.selected = true;
        }
      });


    const inputsRoles = document.querySelectorAll('#modalEditar .checkboxClass');
    // Ambas responsabilidades de agregar los roles y visualizarlos se pueden separar.
    inputsRoles.forEach((check) => {
      if (dataEmpleado.rolesAsociados.length > 0) {
        let rolAsociado = dataEmpleado.rolesAsociados.find(
          (rlA) => parseInt(rlA.idRol) === parseInt(check.value)
        );
        if (rolAsociado) {
          check.checked = true;
          addRol(check.value, true);
        } else {
          check.checked = false;
          addRol(check.value, false);
        }
      }


      check.addEventListener("change", (f) => {
        f.stopPropagation();
        let value = f.target.value;
        if (f.target.checked) {
          addRol(value, true);
        } else {
          addRol(value, false);
        }
      });
    });

    const inputBoletin = document.querySelector("#checkboxBoletin");
    inputBoletin.checked = parseInt(dataEmpleado.boletin) === 1 ? true : false;

    inputNombre.value = dataEmpleado.nombreEmpleado;
    inputEmail.value = dataEmpleado.email;
    inputDescripcion.value = dataEmpleado.descripcion;
    idEmpleado.value = dataEmpleado.idEmpleado;


    // mostrar modal
    modalEditar.style.display = "flex";
  }
});

// Envio de formulario update
modalFormEdit.addEventListener('submit', async (e) => {
  e.preventDefault();

  const dataForm = new FormData(modalFormEdit);
  let data = Object.fromEntries(dataForm.entries());
  // validar que se haya seleccionado una opción de los roles.
  if (rolesAgregados.length === 0) {
    alert('El rol debe ser obligatorio');
    return;
  }

  if (!validateData(dataForm, "boletin", inputsFormEdit)) return;

  if (!validateNombre(data.nombre)) {
    alert('Valor digitado del nombre incorrecto');
    return;
  }

  if (!validateEmail(data.email)) {
    alert('Correo digitado incorrectamente');
    return;
  }
  data = {
    ...data,
    rolesAgregados
  };
  const responseData = await sendData("App/Modules/Empleados/Controller/EmpleadosController.php", "POST", 'updateEmpleado', data);

  if (!responseData.status) {
    alert(responseData.message);
    return;
  }

  alert(responseData.message)
  getUsers = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getEmpleados" }
  );
  renderEmpleados(getUsers);
  modalEditar.style.display = "none";
  rolesAgregados.length = 0;

});

// Mapeo del formulario
const camposFormulario = {
  idEmpleado: "ID del Empleado",
  nombre: "Nombre Completo",
  email: "Correo Electrónico",
  genero: "Sexo",
  area_id: "Área",
  descripcion: "Descripción de la Experiencia",
  boletin: "Desea Recibir Boletín Informativo"

};

modalFormCrear.addEventListener('submit', async (g) => {
  g.preventDefault();
  g.stopPropagation();
  let dataSubmit = new FormData(g.target);
  let data = Object.fromEntries(dataSubmit);

  if (!validateData(dataSubmit, "boletin", camposFormulario)) return;

  // Validar si se selecciono el genero.
  const generoSeleccionado = document.querySelector('input[name="genero"]:checked');

  if (!generoSeleccionado) {
    alert('El genero es obligatorio');
    return;
  }

  if (rolesAgregados.length === 0) {
    alert("El rol debe ser obligatorio");
    return;
  }

  if (!validateNombre(data.nombre)) {
    alert("Valor digitado del nombre incorrecto");
    return;
  }

  if (!validateEmail(data.email)) {
    alert("Correo digitado incorrectamente");
    return;
  }

  data = {
    ...data,
    rolesAgregados
  }

  delete data.idRol;

  const responseAdd = await sendData("App/Modules/Empleados/Controller/EmpleadosController.php", "POST", 'addEmpleado', data);

  if (!responseAdd.status) {
    alert(responseAdd.message);
    return;
  }

  alert(responseAdd.message);
  getUsers = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getEmpleados" }
  );
  renderEmpleados(getUsers);
  modalCrear.style.display = "none";


});


document.addEventListener("DOMContentLoaded", async () => {
  // petición fetch
  getUsers = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getEmpleados" }
  );
  renderEmpleados(getUsers);
  getAreas = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getAreas" }
  );
  // renderAreas(getAreas);
  getRoles = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getRoles" }
  );
  // renderRoles(getRoles);
});
