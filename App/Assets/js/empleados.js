import { getData, sendData } from "./cases/fetch.js";
import {  validateEmail, validateNombre } from "./cases/regex.js";
import { validateData } from "./cases/utils.js";

// Variable global para guardar los datos de los usuarios y poder re utilizarlos en caso de ser necesario.
let getUsers;
const tbodyEmpleados = document.querySelector("#tbodyEmpleados");
const btnCrearEmpleado = document.querySelector("#btnCrearEmpleado");
const closeBtnEditar = document.querySelector("#closeBtnEditar");
const closeBtnCrear = document.querySelector("#closeBtnCrear");
const modalCrear = document.querySelector("#modalCrear");
const modalEditar = document.querySelector("#modalEditar");
const modalFormEdit = document.querySelector('#formEditar');
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
    console.warn("no hay registros");
    return;
  }
  const data = getUsers.data;
  console.log(data);
  const fragment = document.createDocumentFragment();
  // console.log(tbodyEmpleados);
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
const renderAreas = (getAreas = {}) => {
  if (!getAreas) {
    console.log("no hay registros");
    return;
  }
  const inputAreaId = document.querySelector("#inputAreaId");
  const data = getAreas.data;
  const message = getAreas.message;
  const frgment = document.createDocumentFragment();
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.innerText = "-- Selecciona un área --";
  defaultOption.disabled = true;
  defaultOption.selected = true;
  frgment.appendChild(defaultOption);

  data.forEach((ar) => {
    let option = document.createElement("option");
    option.setAttribute("class", "areaOption");
    option.value = ar.idArea;
    option.innerText = ar.nombreArea;
    frgment.appendChild(option);
  });
  inputAreaId.appendChild(frgment);
};
const renderRoles = (getRoles = {}) => {
  if (!getRoles) {
    console.log("no hay registros");
    return;
  }
  const data = getRoles.data;
  const frgment = document.createDocumentFragment();
  const roles = document.querySelector(".roles");
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
const addRol = (idRol, isAdd = false)=>{
    if (!idRol) {
        console.warn('rol no definido para agregar');
        return;
    }
    if (isAdd) {
        // Evitar duplicado
        if (!rolesAgregados.includes(idRol)) {
            rolesAgregados.push(idRol);
            
        }
        console.log({"rolesPush": rolesAgregados});
    }else{
        // Aplicar filter.
        rolesAgregados = rolesAgregados.filter((rl)=> idRol != rl);
        console.log({"rolesFilter": rolesAgregados});
    }

    console.log(rolesAgregados);

}

btnCrearEmpleado.addEventListener("click", (f) => {
  f.stopPropagation();
  f.preventDefault();
  console.log(f.target);

  modalCrear.style.display = "flex";
});

closeBtnCrear.addEventListener("click", (e) => {
  console.log(e.target);
  e.stopPropagation();
  modalCrear.style.display = "none";
});

closeBtnEditar.addEventListener("click", (e) => {
  e.stopPropagation();
  modalEditar.style.display = "none";
//   Limpio el arreglo cada vez que se cierre el modal.
  rolesAgregados.length = 0;
  console.log(rolesAgregados);
});

tbodyEmpleados.addEventListener("click", (event) => {
  if (
    event.target.tagName === "BUTTON" &&
    event.target.classList.contains("btnModificar")
  ) {
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
modalFormEdit.addEventListener('submit',async  (e) => {
    e.preventDefault();

    const dataForm = new FormData(modalFormEdit);
    let data = Object.fromEntries(dataForm.entries());
    // validar que se haya seleccionado una opción de los roles.
    if (rolesAgregados.length === 0) {
        alert('El rol debe ser obligatorio');
        return;
    }

    if(!validateData(dataForm,"boletin",inputsFormEdit)) return;

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
    const responseData = await sendData("App/Modules/Empleados/Controller/EmpleadosController.php","POST",'updateEmpleado', data);

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

document.addEventListener("DOMContentLoaded", async () => {
  // petición fetch
  getUsers = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getEmpleados" }
  );
  renderEmpleados(getUsers);
  const getAreas = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getAreas" }
  );
  renderAreas(getAreas);
  const getRoles = await getData(
    "App/Modules/Empleados/Controller/EmpleadosController.php",
    "GET",
    { action: "getRoles" }
  );
  renderRoles(getRoles);
});
