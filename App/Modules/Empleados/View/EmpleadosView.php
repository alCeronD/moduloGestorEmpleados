<!-- Vista de html de empleados -->
<H1>Gestor de empleados</H1>
<BUTTON id="btnCrearEmpleado">Crear</BUTTON>
<div class="content">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Sexo</th>
                <th>Área</th>
                <th>Boletín</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <!-- Renderizado con javascript. -->
        <tbody id="tbodyEmpleados"></tbody>
    </table>
</div>

<!-- Modal de edición -->
<div id="modalEditar" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="headerContent">
            <span class="close" id="closeBtnEditar">&times;</span>
            <p>Editar Información Empleado</p>
        </div>
        <div class="bodyContent">
            <form action="" id="formEditar">
                <input type="hidden" name="idEmpleado" id="idEmpleado">
                <div class="nombreCompleto">
                    <label for="">Nombre Completo *</label>
                    <input type="text" name="nombre" placeholder="Nombre completo del empleado" id="nombreEditar">
                </div>
                <div class="correoElectronico">
                    <label for="">Correo electrónico *</label>
                    <input type="text" name="email" placeholder="Correo electrónico" id="emailEditar">
                </div>
                <div class="genero">
                    <label for="">Sexo *</label>
                    <input type="radio" value="M" name="genero">Másculino
                    <input type="radio" value="F" name="genero">Femenino
                </div>
                <div class="area">
                    <label for="">Área *</label>
                    <select name="area_id" id="inputAreaId"></select>
                </div>
                <div class="descripcion">
                    <label for="">Descripción * </label>
                    <textarea name="descripcion" id="inputDescripcion" placeholder="Descripción de la experiencia del empleado"></textarea>
                </div>
                <div class="boletin">
                    <input type="checkbox" name="boletin" id="checkboxBoletin" value="1"> <span>Deseo recibir boletín informativo</span>
                </div>
                <div class="roles">
                    <!-- renderizado con js -->
                </div>
                <div class="btnEnviar">
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de creación -->
<div id="modalCrear" class="modal">

    <!-- Modal content -->
         <div class="modal-content">
        <div class="headerContent">
            <span class="close" id="closeBtnCrear">&times;</span>
            <p>Crear Registro</p>
        </div>
        <div class="bodyContent">
            <form action="" id="formCrear">
                <!-- <input type="hidden" name="idEmpleado" id="idEmpleado"> -->
                <div class="nombreCompleto">
                    <label for="">Nombre Completo *</label>
                    <input type="text" name="nombre" placeholder="Nombre completo del empleado" id="nombreCrear">
                </div>
                <div class="correoElectronico">
                    <label for="">Correo electrónico *</label>
                    <input type="text" name="email" placeholder="Correo electrónico" id="emailCrear">
                </div>
                <div class="genero">
                    <label for="">Sexo *</label>
                    <input type="radio" value="M" name="genero">Másculino
                    <input type="radio" value="F" name="genero">Femenino
                </div>
                <div class="area">
                    <label for="">Área *</label>
                    <select name="area_id" id="inputAreaId"></select>
                </div>
                <div class="descripcion">
                    <label for="">Descripción * </label>
                    <textarea name="descripcion" id="inputDescripcion" placeholder="Descripción de la experiencia del empleado"></textarea>
                </div>
                <div class="boletin">
                    <input type="checkbox" name="boletin" id="checkboxBoletin" value="1"> <span>Deseo recibir boletín informativo</span>
                </div>
                <div class="roles">
                    <!-- renderizado con js -->
                </div>
                <div class="btnEnviar">
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>