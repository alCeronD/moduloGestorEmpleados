import { getData } from "./cases/fetch.js";

const tbodyEmpleados = document.querySelector('#tbodyEmpleados');

const renderEmpleados = (getUsers = {})=>{
    if (!getUsers) {
        console.log("no hay registros");
        return;
    }
    
    const data = getUsers.data;
    const message = getUsers.message;
    console.log(message);

    const fragment = document.createDocumentFragment();
    // console.log(tbodyEmpleados);
    tbodyEmpleados.innerHTML = "";
    data.forEach((user)=>{
        console.log(user);
        let btnModificar = document.createElement('button');
        let btnEliminar = document.createElement('button');
        btnEliminar.setAttribute('idEmpleado', `${user.idEmpleado}`);
        btnEliminar.innerText = "Eliminar";
        btnModificar.innerText = "Modificar";
        let tr = document.createElement('tr');

        let tdNombre = document.createElement('td');
        let tdEmail = document.createElement('td');
        let tdSexo = document.createElement('td');
        let tdArea = document.createElement('td');
        let tdBoletin = document.createElement('td');
        let tdModificar = document.createElement('td');
        let tdEliminar = document.createElement('td');

        tdNombre.innerText = user.nombreEmpleado;
        tdEmail.innerText = user.email;
        tdSexo.innerText = user.genero;
        tdArea.innerText = user.nombreArea;
        tdBoletin.innerText = user.boletin === 1 ? "Si" : "No";
        tdModificar.append(btnModificar);
        tdEliminar.append(btnEliminar);
        tr.append(tdNombre,tdEmail,tdSexo,tdArea,tdBoletin,tdModificar, tdEliminar);

        fragment.append(tr);

    });

    tbodyEmpleados.append(fragment);




}


document.addEventListener('DOMContentLoaded', async ()=>{
    // petición fetch
    const getUsers = await getData('App/Modules/Empleados/Controller/EmpleadosController.php','GET', {action: 'getEmpleados'});

    renderEmpleados(getUsers);

});