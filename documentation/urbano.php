tion: scrolling 3s cubic-bezier(0.25, 0.75, 0.75, 0.25) infinite;
        }
        @keyframes scrolling {
            0% { transform: translateX(100%) }
            100% { transform: translateX(-100%) }
        }


    </style>
    <div>
                <style>
            #input_panel_principal{display:none;position:relative;z-index: 2;}
            #input_panel_principal + #panel_principal{
                display: block;
                margin-top: 5px;
                margin-bottom: 5px;
                height:0px;
                overflow: hidden;
                transition: height .5s;
                /* & div{
                    opacity: 0;
                    transition : opacity .5s cubic-bezier(0, 1, 1, 1);
                } */
            }
            
            #input_panel_principal:checked + #panel_principal{
                margin-top: 5px;
                margin-bottom: 5px;
                height:100px;
                transition: height .5s;
                /* & div{
                    opacity: 1;
                    transition : opacity .5s cubic-bezier(1, 0, 1, 1);
                } */
            }
        </style>
        <input type="checkbox" id="input_panel_principal">
        <div id="panel_principal">
            <div id="panel_principal_contenido">
               

                    <div id="intercambio" style="min-width: 40px;display: flex;justify-content: center;">
                        <i id="intercambiar" style="position:relative; -moz-transform: rotate(90deg);
                                -o-transform: rotate(90deg);
                                -webkit-transform: rotate(90deg);
                                transform: rotate(90deg);" class="fas fa-exchange-alt w3-xlarge">
                        </i>
                    </div>
                    <div style="display: flex; width: 250px; height: 105px;flex-direction: column;justify-content: space-around;">
                        <div style="background-color: #f1f1f1;
                            padding: 3px;
                            border-radius: 17px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="">
                                    <img id="origen_icono" src="./img/direccion_A3.png" style="height:30px; width:30px">
                                </div>
                                
                                    <input onfocus="focusInput('origen')" autocomplete="off" id="input_origen"
                                    style="background-color:unset; border: unset; font-size: 1em;" type="text">
                                
                                <div style="height: 21px;" id="localiza_origen">
                                    <i class="fa-solid fa-location-crosshairs" style="
                                    color:#009EE2 !important;;
                                    font-size: 1.4em;"></i>
                                </div>
                            </div>
                        </div>
                        <div style="background-color: #f1f1f1;
                            padding: 3px;
                            border-radius: 17px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="">
                                    <img id="destino_icono" src="./img/direccion_B3.png" style="height:30px; width:30px">
                                </div>
                                <input onfocus="focusInput('destino')" autocomplete="off"  id="input_destino"
                                style="background-color:unset; border: unset; font-size: 1em;" type="text">
                                <div style="height: 21px;" id="localiza_destino">
                                    <i class="fa-solid fa-location-crosshairs" style="
                                    color:#009EE2 !important;;
                                    font-size: 1.4em;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
              
                    <div style="margin: auto;">
                        <button id="buscar" onclick="buscarlineas()" style="border-radius: 50%;
                            width: 40px;
                            height: 40px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            border: none;
                            color: white;
                            background-color:#009EE2 !important;;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                
            </div>
            <div onclick="toggleMenu('ocultar')" style="display:none; padding:0px;margin-top:-40px;float:right" id="toggle_button" estado="maximizado">
                <img src="./img/upwhite.png" width="30px" height="30px" alt="">
            </div>
        </div>
        
        <div class="w3-card-4 w3-small w3-round-large w3-display-bottommiddle" id="indicaciones_panel" oculto="no" 
            style="position: absolute;
            width: 320px;
            background-color: rgba(0, 0, 0, 0);
            display: block;
            bottom: 90px;">
            <div id="indicaciones" class="w3-col" style="background-color:rgba(0,0,0,0)"></div>
            
        </div>
        <div style="position: absolute;
            bottom: 0px;
            width: 100%;
            /* height: 34px; */
            background-color: #009EE2;
            white-space: nowrap;
            overflow: auto;">
                        <div id="leyenda_segun_diagrama" style="color: white;
                font-size: 11px;
                white-space: normal;
                padding:0 8px;">
                *SEGUN DIAGRAMA:Tiempo informado por la empresa seg&uacute;n programaci&oacute;n diaria. No es horario por gps
            </div>
            
							<style>
					input[name="menuBottomOptions"] + label {
						display: flex;
						flex-direction: column;
						align-items: center;
						cursor: pointer;
						width: 100px;
						color: inherit;
						transition: color .3s linear;
					}

					input[name="menuBottomOptions"]:checked + label {
						color: #009EE2;
					}

					input[name="menuBottomOptions"]:hover + label {
						color: #009EE2;
						transition: all .3s linear;
					}
				</style>
				<div id="menuInferior" style="
					width: 100%;
					background-color: var(--primary-bg-color);
					color: var(--primary-font-color);
					display: flex;
					height: 50px;
					flex-direction: row;
					justify-content: center;
					align-items: center;
					font-size: 1.1em;">
					<input type="radio" name="menuBottomOptions" id="inputVerLineas" style="display:none" checked>
					<label id="labelInputVerLineas" for="inputVerLineas">
						<i class="fa-solid fa-house"></i>
						<div>
							Inicio
						</div>
					</label>

					<input type="radio" name="menuBottomOptions" id="inputBuscarLineas" style="display:none">
					<label for="inputBuscarLineas" onclick="document.getElementById('input_panel_principal').checked = true">
						<i class="fa-solid fa-magnifying-glass"></i>
						<div>
							Buscar
						</div>
					</label>

					<input type="radio" name="menuBottomOptions" id="inputParadasCercanas" style="display:none">
					<label for="inputParadasCercanas">
						<i class="fa-solid fa-map"></i>
						<div>
							Cerca mio
						</div>
					</label>
                    <div id="option_calificar" style="display:none">
                        <input type="radio" name="menuBottomOptions" id="inputCalificacion" style="display:none">
                        <label for="inputCalificacion">
                            <i class="fa-solid fa-star"></i>
                            <div>
                                Calificar
                            </div>
                        </label>
                    </div>
				</div>
        </div>


<style>
    #dialog-box {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #ffffff;
      max-width: 500px;
      width: 100%;
      padding: 1.5rem 1.8rem 1.8rem;
      border-radius: 28px;
      box-shadow: 0 20px 40px -12px rgba(0, 20, 40, 0.35), 
                  0 8px 24px -6px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(2px);
      font-family: 'Segoe UI', Roboto, system-ui, -apple-system, sans-serif;
      z-index: 1000;
    }

    .dialog-header {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.2rem;
      border-bottom: 2px solid #eef3f9;
      padding-bottom: 0.7rem;
    }

    .dialog-header i {
      font-size: 1.4rem;
      color: #f5b342;
      background: #fff1d6;
      padding: 0.4rem;
      border-radius: 12px;
    }

    .dialog-header h2 {
      font-weight: 600;
      font-size: 1.3rem;
      letter-spacing: -0.2px;
      color: #1a2639;
      margin: 0;
    }

    .dialog-header span {
      margin-left: auto;
      font-size: 0.75rem;
      background: #f0f4fe;
      padding: 0.15rem 0.7rem;
      border-radius: 30px;
      color: #2b4c7c;
      font-weight: 500;
    }

    .rating-form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    /* Fila para linea y coche numero */
    .form-row {
      display: flex;
      gap: 0.8rem;
    }
    .form-row .form-group {
      flex: 1;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }

    .form-group label {
      font-weight: 500;
      font-size: 0.82rem;
      color: #1f2b3e;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .form-group label i {
      color: #3b6cb0;
      font-size: 0.75em;
      width: 1em;
      opacity: 0.8;
    }

    .required-star {
      color: #d14545;
      font-weight: 700;
      margin-left: 2px;
      font-size: 0.95rem;
    }

    .required-indicator {
      font-size: 0.6rem;
      background: #fde8e8;
      color: #b13e3e;
      padding: 0.05rem 0.5rem;
      border-radius: 30px;
      font-weight: 500;
      margin-left: 6px;
    }

    .opcional-tag {
      font-weight: 400;
      font-size: 0.65rem;
      color: #6b7f99;
      background: #eef3f9;
      padding: 0.05rem 0.6rem;
      border-radius: 40px;
      margin-left: 6px;
    }

    select, input[type="date"], input[type="time"], input[type="number"] {
      width: 100%;
      padding: 0.45rem 0.8rem;
      border: 1.5px solid #dfe7f0;
      border-radius: 12px;
      background: #fafcff;
      font-size: 0.85rem;
      color: #1f2a3f;
      transition: 0.2s ease;
      font-family: inherit;
      appearance: auto;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
      height: 2.3rem;
    }

    select:focus, input[type="date"]:focus, input[type="time"]:focus, input[type="number"]:focus {
      border-color: #4d7bc0;
      outline: none;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(65, 110, 190, 0.10);
    }

    select {
      /* background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%233b6cb0" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>'); */
      background-repeat: no-repeat;
      background-position: right 0.8rem center;
      background-size: 0.9rem;
      padding-right: 2.2rem;
      cursor: pointer;
    }

    .stars-group {
      display: flex;
      flex-direction: column;
      gap: 0.15rem;
      font-size: 1.5em;
    }

    .stars-group .stars-label {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .stars-group .stars-label label {
      font-size: 0.82rem;
    }

    .star-rating {
      display: flex;
      flex-direction: row-reverse;
      justify-content: flex-end;
      gap: 0.15rem;
      padding: 0.2rem 0 0.1rem;
    }

    .star-rating input {
      display: none;
    }

    .star-rating label {
      font-size: 1.8rem;
      color: #cfd9e6;
      cursor: pointer;
      transition: color 0.15s ease, transform 0.1s;
      padding: 0 0.05rem;
      line-height: 1;
    }

    /* Estrella marcada (checked) - se pinta en amarillo */
    .star-rating input:checked ~ label i {
      color: #f5b342 !important;
    }

    .star-rating label:hover i,
    .star-rating label:hover ~ label i {
      color: #f5b342 !important;
      transform: scale(1.06);
    }

    .star-rating input:checked + label i,
    .star-rating input:checked + label:hover i,
    .star-rating input:checked ~ label i,
    .star-rating input:checked ~ label:hover i {
      color: #f5b342 !important;
    }

    .star-rating label i {
      text-shadow: 0 1px 4px rgba(0,0,0,0.02);
      transition: color 0.15s ease, transform 0.15s;
    }

    textarea {
      width: 100%;
      padding: 0.5rem 0.8rem;
      border: 1.5px solid #dfe7f0;
      border-radius: 12px;
      font-family: inherit;
      font-size: 0.82rem;
      resize: vertical;
      min-height: 60px;
      background: #fafcff;
      transition: 0.2s ease;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
    }

    textarea:focus {
      border-color: #4d7bc0;
      outline: none;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(65, 110, 190, 0.08);
    }

    textarea::placeholder {
      color: #9aabbf;
      font-weight: 300;
      font-size: 0.8rem;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.8rem;
      margin-top: 0.3rem;
      border-top: 1px solid #eef3f9;
      padding-top: 1rem;
    }

    .btn {
      padding: 0.45rem 1.4rem;
      border-radius: 60px;
      border: none;
      font-weight: 600;
      font-size: 0.82rem;
      background: #eef3f9;
      color: #1f2b3e;
      cursor: pointer;
      transition: 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.02);
      height: 2.2rem;
    }

    .btn.disabled {
      opacity: 0.6;
      pointer-events: none;
      cursor: not-allowed;
      box-shadow: none;
      background: #4e5666;
      color: white;
    }

    .btn-primary {
      background: #1f3a6b;
      color: white;
      box-shadow: 0 6px 12px -4px rgba(25, 55, 110, 0.25);
    }

    .btn-primary:hover {
      background: #15315c;
      transform: scale(1.01);
      box-shadow: 0 8px 14px -6px #1f3a6b55;
    }

    .btn-secondary {
      background: #eef3f9;
      color: #1f2b3e;
    }

    .btn-secondary:hover {
      background: #e2e9f2;
    }

    .btn i {
      font-size: 0.75rem;
    }

    .error-highlight {
      border-color: #d14545 !important;
      background: #fff7f7 !important;
    }

    input[name="dialog_calificacion"] {
        display: none;
    }

    input[name="dialog_calificacion"] + .dialog_box {
        display: none;
    }

    input[name="dialog_calificacion"]:checked + .dialog_box {
        display: block;
    }

</style>
 
  <div id="dialog-box" style="display: none;">
    <input type="radio" name="dialog_calificacion" checked>
    <div class="dialog_box" id="dialog_calific">
        <div class="dialog-header">
        <i class="fas fa-star-half-alt"></i>
        <h2>Califica tu experiencia</h2>
        </div>

        <form id="ratingForm" class="rating-form" novalidate>
        <!-- Fila: Linea + Coche numero -->
        <div class="form-row">
            <div class="form-group">
            <label for="linea">
                <i class="fas fa-road"></i> L&iacute;nea 
                <span class="required-star">*</span>
                <span class="required-indicator">obligatorio</span>
            </label>
            <select id="linea" name="linea" required placeholder="Selecciona una l&iacute;nea">
            </select>
            </div>

            <div class="form-group">
            <label for="coche_nro">
                <i class="fas fa-car"></i> Coche n&#8304;
                <span class="opcional-tag">opcional</span>
            </label>
            <input type="number" id="coche_nro" name="coche_nro" placeholder="N&uacute;mero" min="1" step="1">
            </div>
        </div>

        <div class="form-row">
            <!-- Fecha (obligatorio) -->
            <div class="form-group">
                <label for="fecha">
                <i class="fas fa-calendar-alt"></i> Fecha 
                <span class="required-star">*</span>
                <span class="required-indicator">obligatorio</span>
                </label>
                <input type="date" id="fecha" name="fecha" required value="2026-10-24">
            </div>

            <!-- Hora (obligatorio) -->
            <div class="form-group">
                <label for="hora_calificar">
                <i class="fas fa-clock"></i> Hora 
                <span class="required-star">*</span>
                <span class="required-indicator">obligatorio</span>
                </label>
                <input type="time" id="hora_calificar" name="hora" required value="09:15">
            </div>
        </div>

        <!-- Calificacion con estrellas (obligatorio) -->
        <div class="form-group stars-group">
            <div class="stars-label">
            <i class="fas fa-star" style="color:#f5b342; font-size:0.85em;"></i>
            <label style="font-weight:500; color:#1f2b3e; font-size:0.82em;">Calificaci&oacute;n <span class="required-star">*</span></label>
            <span class="required-indicator">obligatorio</span>
            </div>
            <div class="star-rating" id="starRatingContainer">
            <input type="radio" id="star5" name="calificacion" value="5" required>
            <label for="star5" title="5 estrellas"><i class="fas fa-star"></i></label>

            <input type="radio" id="star4" name="calificacion" value="4">
            <label for="star4" title="4 estrellas"><i class="fas fa-star"></i></label>

            <input type="radio" id="star3" name="calificacion" value="3">
            <label for="star3" title="3 estrellas"><i class="fas fa-star"></i></label>

            <input type="radio" id="star2" name="calificacion" value="2">
            <label for="star2" title="2 estrellas"><i class="fas fa-star"></i></label>

            <input type="radio" id="star1" name="calificacion" value="1">
            <label for="star1" title="1 estrella"><i class="fas fa-star"></i></label>
            </div>
            <small style="color:#5f7392; font-size:0.65em; margin-top:1px;">Selecciona una estrella para puntuar</small>
        </div>

        <!-- Comentario (opcional) -->
        <div class="form-group">
            <label for="comentario">
            <i class="fas fa-pencil-alt"></i> Comentario 
            <span class="opcional-tag">opcional</span>
            </label>
            <textarea id="comentario" name="comentario" rows="2" placeholder="Cu&eacute;ntanos m&aacute;s... (opcional)"></textarea>
        </div>

        <div class="form-actions">
            <button id="btn_clear_form_comentario" type="reset" class="btn btn-secondary"><i class="fas fa-undo-alt"></i> Limpiar</button>
            <button id="btn_send_rating" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
        </div>

        </form>
    </div>
    <input type="radio" name="dialog_calificacion" id="input_dialog_repuesta">
    <div class="dialog_box" id="dialog_repuesta">
        <i class="fas fa-check-circle"></i> <span id="feedbackText">Calificaci&oacute;n enviada correctamente.</span>
    </div>
  
  <script>
    let timer_calificar = null;

    (function() {
        const option_calificar = document.getElementById('option_calificar');
        const form = document.getElementById('ratingForm');

        const linea = document.getElementById('linea');
        const fecha = document.getElementById('fecha');
        const hora = document.getElementById('hora_calificar');
        const cocheNro = document.getElementById('coche_nro');
        const stars = document.querySelectorAll('input[name="calificacion"]');

        const btn_clear_form_comentario = document.getElementById('btn_clear_form_comentario');
        const btn_send_rating = document.getElementById('btn_send_rating');

        function clearErrors() {
            [linea, fecha, hora].forEach(el => el.classList.remove('error-highlight'));
            const starContainer = document.getElementById('starRatingContainer');
            starContainer.style.border = 'none';
            starContainer.style.padding = '0';
            starContainer.style.borderRadius = '0';
            starContainer.style.background = 'transparent';
        }
        
        function validateForm() {
            let isValid = true;
            clearErrors();

            if (!linea.value || linea.value === '') {
            linea.classList.add('error-highlight');
            isValid = false;
            }

            if (!fecha.value) {
            fecha.classList.add('error-highlight');
            isValid = false;
            }

            if (!hora.value) {
            hora.classList.add('error-highlight');
            isValid = false;
            }

            let starChecked = false;
            for (let star of stars) {
            if (star.checked) {
                starChecked = true;
                break;
            }
            }
            if (!starChecked) {
            const starContainer = document.getElementById('starRatingContainer');
            starContainer.style.border = '2px solid #d14545';
            starContainer.style.padding = '0.3rem 0.5rem';
            starContainer.style.borderRadius = '16px';
            starContainer.style.background = '#fff7f7';
            isValid = false;
            } else {
            const starContainer = document.getElementById('starRatingContainer');
            starContainer.style.border = 'none';
            starContainer.style.padding = '0';
            starContainer.style.background = 'transparent';
            }

            return isValid;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (validateForm()) {
                const formData = {
                    linea: linea.value,
                    coche_nro: cocheNro.value || null,
                    fecha: fecha.value,
                    hora: hora.value,
                    calificacion: document.querySelector('input[name="calificacion"]:checked')?.value || '',
                    comentario: document.getElementById('comentario').value
                };
                const url = `urbano2_cmd.php?cmd=calificacion`;
                const params = new FormData();
                for (const [key, value] of Object.entries(formData)) {
                    params.append(key, value);
                }
                                params.append("conf", "cbaciudad");
                if (typeof(Storage) !== "undefined") {
                    userkey = localStorage.getItem("userkey");
                } else {
                    userkey = '';
                }
                params.append("userkey", userkey);

                btn_send_rating.disabled = true;
                btn_send_rating.classList.add('disabled');
                btn_clear_form_comentario.disabled = true;
                btn_clear_form_comentario.classList.add('disabled');

                const a = await nfetch(url, {
                    method: 'POST',
                    body: new URLSearchParams(params),
                    timeout: fetchTimeout,
                });
                let data;

                if (a.status == 408){
                    location.reload();
                    return;
                }
                if(a.ok){
                    data = await a.json();
                }

                const input_dialog_repuesta = document.getElementById('input_dialog_repuesta');
                input_dialog_repuesta.checked = true;
                
                option_calificar.style.display = 'none';
                timer_calificar = setTimeout(() => {
                    document.getElementById('labelInputVerLineas').click();
                }, 10 * 1000);
                clearErrors();
            } else {
                const firstError = document.querySelector('.error-highlight, #starRatingContainer');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

      form.addEventListener('reset', function(e) {
        setTimeout(() => {
          clearErrors();
          document.querySelectorAll('input[name="calificacion"]').forEach(r => r.checked = false);
          const starContainer = document.getElementById('starRatingContainer');
          starContainer.style.border = 'none';
          starContainer.style.padding = '0';
          starContainer.style.background = 'transparent';
          linea.value = '';
          cocheNro.value = '';
          fecha.value = '2026-10-24';
          const ahora = new Date();
          hora.value = String(ahora.getHours()).padStart(2, '0') + ':' + String(ahora.getMinutes()).padStart(2, '0');
        }, 10);
      });

      form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('change', function() {
          if (this.classList.contains('error-highlight')) {
            this.classList.remove('error-highlight');
          }
          if (this.name === 'calificacion') {
            const starContainer = document.getElementById('starRatingContainer');
            starContainer.style.border = 'none';
            starContainer.style.padding = '0';
            starContainer.style.background = 'transparent';
          }
        });
      });

      clearErrors();
      btn_clear_form_comentario.click();
    })();
  </script>

				<script>
                    // console.log('type storage:', typeof(Storage));
                    // if (typeof(Storage) !== "undefined") {
                    //     console.log('logged_ok:', localStorage.getItem("logged_ok"));
                    //     if(localStorage.getItem("logged_ok") == 'ok'){
                    //         const menuInferior = document.getElementById('menuInferior');
                    //         const input_calificacion = document.createElement('input');
                    //         input_calificacion.setAttribute('type', 'radio');
                    //         input_calificacion.setAttribute('name', 'menuBottomOptions');
                    //         input_calificacion.setAttribute('id', 'inputCalificacion');
                    //         input_calificacion.setAttribute('style', 'display:none');
                    //         const label_calificacion = document.createElement('label');
                    //         label_calificacion.setAttribute('for', 'inputCalificacion');
                    //         const icon_calificacion = document.createElement('i');
                    //         icon_calificacion.setAttribute('class', 'fa-solid fa-star');
                    //         const div_calificacion = document.createElement('div');
                    //         div_calificacion.innerHTML = 'Calificar';
                    //         label_calificacion.appendChild(icon_calificacion);
                    //         label_calificacion.appendChild(div_calificacion);
                    //         menuInferior.appendChild(input_calificacion);
                    //         menuInferior.appendChild(label_calificacion);
                    //     }
                    // }

                    
					// buscar_lineas_fn();
					const botones_menu_inferior = document.querySelectorAll('#menuInferior input[name="menuBottomOptions"]');

					for (let i = 0; i < botones_menu_inferior.length; i++) {
						botones_menu_inferior[i].addEventListener('change', () => {
                            if(document.getElementById('input_direcciones_favoritas')){
                                document.getElementById('input_direcciones_favoritas').checked = false;
                            }
							for (let j = 0; j < botones_menu_inferior.length; j++) {
								if(botones_menu_inferior[j].checked == true){
									if (botones_menu_inferior[j].id == 'inputVerLineas'){
										ver_lineas_fn();
                                        clearTimeout(timer_calificar);
										break;
									}
									if (botones_menu_inferior[j].id == 'inputBuscarLineas'){
										buscar_lineas_fn();
                                        clearTimeout(timer_calificar);
										break;
									}
									if (botones_menu_inferior[j].id == 'inputParadasCercanas'){
										ver_paradas_fn();
                                        clearTimeout(timer_calificar);
										break;
									}
                                    if (botones_menu_inferior[j].id == 'inputCalificacion'){
                                        calificar_fn();
                                        clearTimeout(timer_calificar);
                                        break;
                                    }
								}
							}
						});
					}
							




				</script>
			
			            
            
        </div>
        <script>
            const guardarLineaFavorita = linea=>{
                const input_iconRutaFav = document.getElementById("input_iconRutaFav");
                let lineas_favoritas = [];
                if (typeof(Storage) !== "undefined") {
                    
                    if (isJSON(localStorage.getItem("lineas_favoritascbaciudad"))){
                        lineas_favoritas = JSON.parse(localStorage.getItem("lineas_favoritascbaciudad"));
                    }

                    if (lineas_favoritas.filter(row=> row.linea_id==linea.linea_id && row.cliente == linea.cliente).length == 0){ // si no existe en favoritos
                        if (input_iconRutaFav.checked){ // si is checked lo agrego
                            lineas_favoritas.push(linea);
                            localStorage.setItem("lineas_favoritascbaciudad",JSON.stringify(lineas_favoritas));    
                        }                            
                    } else { // si ya existe en favoritos
                        if (!input_iconRutaFav.checked){ // si no is checked lo quito
                            lineas_favoritas = lineas_favoritas.filter(row=> row.linea_id!=linea.linea_id || row.cliente != linea.cliente);
                            localStorage.setItem("lineas_favoritascbaciudad",JSON.stringify(lineas_favoritas));    
                        }
                    }
                    
                }
            }
        </script>
        
		            <style>
                input#input_iconRutaFav{
                    display:none;
                    & + label > i{
                        color:grey;
                        transition: all .3s linear;
                    }
                }
                input#input_iconRutaFav:checked + label > i{
                    color:#ffb100;
                    transition: all .3s linear;
                }
            </style>
			<input type="checkbox" name="" id="input_listado_rutas_contenedor">
			<div id="listado_rutas_contenedor">
				<div style="width: 70px; min-width: 70px;"></div>
				<div style="max-width: calc(100vw - 70px); margin-left: auto; width: auto;">
					<div id="rutaFav" style="
						position: relative;
						background-color: var(--primary-bg-color);
						color: var(--primary-font-color);
						/* max-width: 360px; */
						border-radius: 0 0 10px 10px;
						height: 30px;
						display: flex;
						align-items: center;
						justify-content: center;
						">
                        <input type="checkbox" name="" id="input_iconRutaFav" onchange="guardarLineaFavorita('linea_10');">
                        <label for="input_iconRutaFav">
                            <i class="fas fa-star iconFav" id="iconRutaFav" style="margin-right: 15px"></i>
                        </label>
						<span id="nombreRutaFav">Linea 10</span>
					</div>
                    <div style="min-width: 360px;"></div>
					<div id='listado_rutas'>
					</div>		
				</div>
			</div>
		
        </div>

        <style>
            #input_direcciones_favoritas{
                display:none;
                z-index: 5;
                position: relative;
                z-index: 4;
            }    
            #input_direcciones_favoritas + div{
                display: flex;
                flex-direction: row;
                position: absolute;
                top: 10px;
                left: 50%;
                transform: translate(-50%, -200%);
                transition: 0.5s all ease;
                & > div{
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    gap: 5px;
                    background-color: var(--primary-bg-color);
                    color: var(--primary-font-color);
                    border-radius: 8px;
                    padding: 5px;
                    width: 100px;
                    height: 40px;
                    overflow: hidden;
                    cursor: pointer;
                    position: relative;
                    transition: 0.5s all ease;
                    margin:0 3px;
                }
            }

            #input_direcciones_favoritas:checked + div{
                transform: translate(-50%, 0px);
                transition: 0.5s all ease;
            }

            #input_btnAceptarDirecFavorita + div{
                background-color: grey;
            }

            #input_btnAceptarDirecFavorita:checked + div{
                background-color: #009ee2;
            }

        </style>

        <input type="checkbox" id="input_direcciones_favoritas" checked>
        <div id="direcciones_favoritas" style="display: none;">
            
        </div>
        <script>
            const dlg = new nDialogo({
                width: '500px',
                top: '50px;',
                background : true,
                backgroundCloseable : true,
            });
            const direccionFavorita = ()=>{
                dlg.open(`<div class="nDialogoBody">
                    <div class="input-group"> 
                        <i class="fas fa-star" style="background: #ffe5c9; color: orange; padding: 4px; border-radius: 10px;"></i>
                        <input autocomplete="off" class="inputSarch" type="text" id="nombreDirecFavorita" placeholder="Ingresa un nombre">
                        <img src="https://cdn-icons-png.flaticon.com/512/10263/10263254.png" alt="" style="height: 22px; right: 10px;cursor:pointer;"
                        onclick="a=document.getElementById('nombreDirecFavorita'); a.value=''; a.focus();
                        document.getElementById('input_btnAceptarDirecFavorita').checked = false;">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-home" style="background: #c9cbff; color: #5656fb; padding: 4px; border-radius: 10px;"></i>
                        <input onkeyup="callebusqueda('direcFavorita')" autocomplete="off" class="inputSarch" type="text" name="direcFavorita" id="direcFavorita" placeholder="Ingresa una direccion">
                        <img id="localiza_origen" src="https://cdn-icons-png.flaticon.com/512/10263/10263254.png" alt="" style="height: 22px; right: 10px;cursor:pointer;" onclick="a=document.getElementById('direcFavorita'); a.value=''; a.focus();
                        document.getElementById('input_btnAceptarDirecFavorita').checked = false;
                        document.getElementById('direcFavorita').removeAttribute('lat');
                        document.getElementById('direcFavorita').removeAttribute('lon');
                        document.getElementById('combo_direcFavorita').innerHTML = '';
                        ">
                    </div>
                    <span id="nombreDirecFavoritaError" style="color: red; display:none; width: 100%;"> *Faltan datos por completar </span>

                    <div style="width:100%;max-height: 200px; overflow:auto;" id="combo_direcFavorita">
                    </div>
                    
                    <div style="display: flex; gap: 5px;">
                        <input type="checkbox" id="input_btnAceptarDirecFavorita" style="display:none;">
                        <div id="btnAceptarDirecFavorita" onclick="validarDatosDirecFav()" style=" display: flex;
                            justify-content: space-evenly;
                            padding: 8px 15px;
                            font-size: 1rem;
                            color: white;
                            border-radius: 12px;
                            outline: none;
                            transition: 0.5s all;
                            font-weight: 500;
                            cursor: pointer;
                            overflow: hidden;
                            position: relative;
                            width: fit-content;
                            gap: 11px;
                            cursor: pointer;
                            align-items: center;
                            margin: 10px 0 0 0;
                            border: 0px;">
                            Aceptar
                        </div>
                        <div class="btnClass btnRed" onclick="dlg.close()" style=" display: flex;
                            background-color: #e22700;
                            justify-content: space-evenly;
                            padding: 8px 15px;
                            font-size: 1rem;
                            color: white;
                            border-radius: 12px;
                            outline: none;
                            transition: 0.5s all;
                            font-weight: 500;
                            cursor: pointer;
                            overflow: hidden;
                            position: relative;
                            width: fit-content;
                            gap: 11px;
                            cursor: pointer;
                            align-items: center;
                            margin: 10px 0 0 0;
                            border: 0px;">
                            Cancelar
                        </div>
                    </div>
            
                </div>`);
                const nombreDirecFavorita = document.getElementById('nombreDirecFavorita');
                nombreDirecFavorita.addEventListener('input', ()=>{
                    const input_btnAceptarDirecFavorita = document.getElementById('input_btnAceptarDirecFavorita');
                    const direcFavorita = document.getElementById('direcFavorita');
                    if (nombreDirecFavorita.value > '' && direcFavorita.getAttribute('lon') > '') {
                        input_btnAceptarDirecFavorita.checked = true;
                    } else {
                        input_btnAceptarDirecFavorita.checked = false;
                    }
                });
            }

            const buscar_dir_fav = ()=>{
                console.log('buscar');
            }

            let items = [
                {
                    label: 'Casa',
                    value: 'casa',
                    icon: 'fas fa-home',
                    color: 'orange',
                    color_background: '#ffe5c9',
                    action: direccionFavorita,
                },
                {
                    label: 'A&ntilde;adir',
                    value: 'anadir',
                    icon: 'fas fa-plus',
                    color: '#3dde03',
                    color_background: '#c9ffd6',
                    action: direccionFavorita,
                }
            ];
            items.forEach(item => {
                let div = document.createElement('div');
                // div.style = ``;
                div.innerHTML = `
                    <i class="${item.icon}" style="background: ${item.color_background};
                        color: ${item.color};
                        padding: 4px;
                        border-radius: 10px;"></i>
                    <span>${item.label}</span>
                `;
                div.addEventListener('click', item.action);
                document.getElementById('direcciones_favoritas').appendChild(div);
            });

            const validarDatosDirecFav = ()=>{
                const input_btnAceptarDirecFavorita = document.getElementById('input_btnAceptarDirecFavorita');
                if (!input_btnAceptarDirecFavorita.checked) {
                    return;
                }
                const nombreDirecFavorita = document.getElementById('nombreDirecFavorita').value;
                if (nombreDirecFavorita === '') {
                    return;
                }
                
                if (typeof(Storage) !== "undefined") {
                    const direcFavorita = document.getElementById('direcFavorita');
                    const nueva_dir_fav = {
                        label: nombreDirecFavorita,
                        value: 'dir_fav',
                        icon: 'fas fa-star',
                        color: '#3dde03',
                        color_background: '#ffe5c9',
                        action: buscar_dir_fav,
                        lat: direcFavorita.getAttribute('lat'),
                        lon: direcFavorita.getAttribute('lon'),
                    }

                    const a = localStorage.getItem("direcciones_favoritascbaciudad");
                    let direcciones_favoritas = [];
                    if (isJSON(a)) {
                        direcciones_favoritas = JSON.parse(a);
                    }
                    direcciones_favoritas.push(nueva_dir_fav);
                    localStorage.setItem("direcciones_favoritascbaciudad", JSON.stringify(direcciones_favoritas));
                }
            }

            
        </script>


        <style>
            #input_listado_lineas {display:none;}
            #input_listado_lineas + #listado_lineas {
                display:flex;
                width:60px;
                flex-direction: column;
                align-items: center;
                translate: -100% 0;
                opacity: 0;
                transition: translate 0.3s ease-in, opacity 0.3s ease-in;
            }
            #input_listado_lineas:checked + #listado_lineas {
                translate: 0 0;
                opacity: 1;
                transition: translate 0.3s ease-out, opacity 0.3s ease-out;
            }
        </style>
        <input type="checkbox" id="input_listado_lineas" checked>
        <div id="listado_lineas" style="
            position:absolute;
			top:0;
            max-height:calc(100vh - 60px);
			overflow-x: hidden;
    		overflow-y: auto;
            overscroll-behavior-y: contain;
        ">
        </div>
               
        <div id="lineas">

        </div>


    </div>

    <script>

        const fetchTimeout = 40000;
        let vistaDeLineas = false;
    
        
        let modoVista = 'verLineas';

        const ocultar_todo = ()=>{
			if (typeof buscar_lineas_btn !== 'undefined') {
            	buscar_lineas_btn.style.display = 'inline-block';
			}
            			if (typeof ver_lineas_btn !== 'undefined') {
				ver_lineas_btn.style.display = 'inline-block';	
			}
            			if (typeof ver_paradas_btn !== 'undefined') {
				ver_paradas_btn.style.display = 'inline-block';	
			}

            // document.getElementById('listado_lineas').style.display = 'none';
            document.getElementById('input_listado_lineas').checked = false;
            document.getElementById('listado_rutas').style.display = 'none';
            document.getElementById('lineas').style.display = 'none';
            document.getElementById('lineas').innerHTML = '';

            ocultarClassName('ruta_');
            ParadasSelec.clear();
                        Paradas.clear();
            Lineas.clear();
            Lineas1.clear();
            Desvios.clear();
            Coches.clear();
            Banderas.clear();
                        Puntos.clear();
            toggleMenu("ocultar");
            document.getElementById('indicaciones').innerHTML = '';
            Caminatas.clear();
            document.getElementById('popup').innerHTML = ``;
			if(document.getElementById('compartir')){
            	document.getElementById('compartir').style.display = 'none';
			}
            trazaSeleccionada = null;
            clearTimeout(ini_coches_ruta);
            document.getElementById('input_panel_principal').checked = false;

            document.getElementById('dialog-box').style.display = 'none';
        }


            
            
        const mostrar_buscar_lineas = ()=>{
			if (typeof buscar_lineas_btn !== 'undefined') {
				buscar_lineas_btn.style.display = 'none';	
			}

            document.getElementById('input_panel_principal').checked = true;
            modoVista = 'buscarLineas';
            toggleMenu("mostrar");
            
            leyenda_segun_diagrama.style.display = 'none';
            
            
        }

                const mostrar_ver_lineas = ()=>{
			if(typeof ver_lineas_btn !== 'undefined') {
				ver_lineas_btn.style.display = 'none';	
			}
            modoVista = 'verLineas';
            clearInterval(actualiza_coches);
            document.getElementById('input_listado_lineas').checked = true;
            document.getElementById('listado_rutas').style.display = 'block';
            unselect_grupolinea();
            leyenda_segun_diagrama.style.display = 'none';
        }
        
        const mostrar_ver_paradas = ()=>{
			if(typeof ver_paradas_btn !== 'undefined') {
				ver_paradas_btn.style.display = 'none';	
			}
            modoVista = 'verParadas';
            ver_paradas();
            leyenda_segun_diagrama.style.display = 'none';
        }

        const mostrar_calificar = ()=>{
            const dialogBox = document.getElementById('dialog-box');
            const hora_calificar = document.getElementById('hora_calificar'); // input type=time
            const ahora = new Date();
            hora_calificar.value = String(ahora.getHours()).padStart(2, '0') + ':' + String(ahora.getMinutes()).padStart(2, '0');
            dialogBox.style.display = 'block';
        }

                const ver_lineas_fn = ()=>{
            if(document.getElementById('input_direcciones_favoritas')){
                document.getElementById('input_direcciones_favoritas').checked = true;
            }
            ocultar_todo();
            mostrar_ver_lineas();
        }
        if(document.getElementById('ver_lineas_btn')){
			document.getElementById('ver_lineas_btn').addEventListener('click', ver_lineas_fn);
		}
        
        const buscar_lineas_fn = ()=>{
            ocultar_todo();
            mostrar_buscar_lineas();
        }
		if(document.getElementById('buscar_lineas_btn')){
			document.getElementById('buscar_lineas_btn').addEventListener('click',buscar_lineas_fn);	
		}
        
        const ver_paradas_fn = ()=>{
            ocultar_todo();
            mostrar_ver_paradas();
        }

        const calificar_fn = ()=>{
            ocultar_todo();
            mostrar_calificar();
        }

		if(document.getElementById('ver_paradas_btn')){
        	document.getElementById('ver_paradas_btn').addEventListener('click',ver_paradas_fn);
		}
                

        const ocultarClassName = className=>{
			const input_listado_rutas_contenedor = document.getElementById('input_listado_rutas_contenedor');
			input_listado_rutas_contenedor.checked = false;
            const a=document.getElementsByClassName(className);
            for(i=0; i< a.length; i++){
                a[i].style.display = 'none';
            }
        }

        const mostrarClassName = className=>{
			const input_listado_rutas_contenedor = document.getElementById('input_listado_rutas_contenedor');
			input_listado_rutas_contenedor.checked = true;
            const a=document.getElementsByClassName(className);
            for(i=0; i< a.length; i++){
                a[i].style.display = 'flex';
				const width = a[i].offsetWidth - 5;
				const width_hijo = a[i].firstElementChild.offsetWidth;
				if(width_hijo > width){
					a[i].firstElementChild.style.animation = 'scroll 8s linear infinite';
				}
            }
        }
        
    </script>




    

    <div id="error" class="w3-container" hidden>
        <div class="w3-display-bottommiddle w3-panel w3-red" style="width:90%;">
            <h3 id="error_titulo" style="float: left;"><i class="fas fa-sad-tear fa-2x"></i> Sin Resultados
                
            </h3>
            <button class="w3-btn w3-white w3-border w3-round-xxlarge w3-theme-d4" style="position:relative; float:left;margin: 20px 0px 0px 10px" onclick="document.getElementById('error').hidden=true;ver_lineas_fn()">
                    <b>Ver Lineas</b>
                </button>
            <p id="error_texto" class="w3-row">No se entrontraron recorridos que unan estas ubicaciones. Prueba nuevamente cambiando el origen o destino, o bien agranda el radio de caminata.
                
            </p>
            
        </div>
    </div>

        <div id="notificacion" style="position: absolute;
        bottom: 60px;
        right: 10px;" hidden>
        <div class="w3-display-bottommiddle w3-panel" style="
            position: relative;
            width: 300px;
            height: 105px;
            right: 10px;
            background-color: var(--primary-bg-color);
            color: var(--primary-font-color);
            margin: 0px;
            padding: 15px 5px;
            border-radius: 15px;
            overflow-y: auto;
            transition: 0.5s all ease;
            box-shadow: 0 0 1px 0 rgb(52 46 173 / 25%), 0 15px 30px 0 rgb(52 46 173 / 11%);
        ">
            <div style="position: absolute;
            right: 0px;
            padding: 7px;
            height: 10px;
            top: -10px;
            font-size: 22px;"
            onclick="document.getElementById('notificacion').hidden = true;"
            ><b>x</b></div>
            <div id="notificacion_texto" style="display:flex; flex-direction: row;">
                
            </div>            
        </div>
    </div>
    


    <div style="position:absolute; width:70%;top:250px;left:0px; margin:2%;background-color:white; max-height:23px; overflow:auto;"
        id="geolocation_test">
    </div>

    

    <div id="pantalla_gris" class="w3-animate-opacity" style="position:absolute; top:0px; width:100%; height:100%; background-color:rgba(0,0,0,.5); display:none">
        <div class="w3-display-middle" style="text-align: center; color: white">
            <i class="fa fa-spinner w3-spin" style="font-size:64px"></i><br>
            <div id="pantalla_gris_msg"></div>
        </div>
        
    </div>

    



    <script>

        const isLoginIn = ()=>{
            if (typeof(Storage) !== "undefined") {
                global_logged = localStorage.getItem("logged");
            }
            if (global_logged == 'ok'){
                return true;
            } else {
                auth.signOut();
                return false;
            }
        }

        const ocultar_mostrar_indicaciones = ()=>{
            if (document.getElementById('indicaciones_panel').getAttribute('oculto') == 'si') {
                document.getElementById('indicaciones_panel').style.width = 'auto';
                document.getElementById('indicaciones_panel').setAttribute('oculto', 'no');
                for (i = 0; i < document.getElementsByClassName('indicacion').length; i++) {
                    document.getElementsByClassName('indicacion')[i].style.display = 'block';
                }
            } else {
                document.getElementById('indicaciones_panel').style.width = '40px';
                document.getElementById('indicaciones_panel').setAttribute('oculto', 'si');
                for (i = 0; i < document.getElementsByClassName('indicacion').length; i++) {
                    document.getElementsByClassName('indicacion')[i].style.display = 'none';
                }
            }
        }
        
        
        var conf = new URL(window.location.href).searchParams.get("conf");
        var a = new URL(window.location.href).searchParams.get("a");
        var j = new URL(window.location.href).searchParams.get("j");
        var j = new URL(window.location.href).searchParams.get("j");
        var usuario = '';
        var posicion = {lat:0,lon:0};

        var sentido = '';
        
        setTimeout(milocalizacion, 1000)
        setInterval(milocalizacion, 60000*10);

        document.getElementById('localiza_origen').addEventListener('click', ()=>{
            milocalizacion('A');

            
        });
        document.getElementById('localiza_destino').addEventListener('click', ()=>{
            milocalizacion('B');
        });

        const compartir = ()=>{
            var lon1 = origen.getGeometry().getCoordinates()[0].toFixed(6);
            var lat1 = origen.getGeometry().getCoordinates()[1].toFixed(6);
            var lon2 = destino.getGeometry().getCoordinates()[0].toFixed(6);
            var lat2 = destino.getGeometry().getCoordinates()[1].toFixed(6);

			const entorno = null;


            if (typeof Android === 'object'){
                Android.compartir(lon1,lat1,lon2,lat2,entorno);
            } else {
                var win = window.open(`whatsapp://send?text=https://${window.location.host}/web/urbano/?conf=cbaciudad%26la1=${lat1}%26lo1=${lon1}%26la2=${lat2}%26lo2=${lon2}%26e=${entorno}`, '_blank');
            }
        }

        const mostrar_combo_boton = or_des=>{
            document.getElementById('combo_boton_' + or_des).focus
            document.getElementById('combo_boton_' + or_des).innerHTML = `
            <div class="w3-theme-l4 w3-padding w3-card w3-round-large" onclick="milocalizacion('${or_des=='origen'?'A':'B'}')"><i class="fas fa-map-marker-alt"></i> Mover el punto ${or_des=='origen'?'A':'B'} a su ubicacion Actual</div>
            `;
            if (typeof(Storage) !== "undefined") {
                if (localStorage.getItem('casa_lon') && localStorage.getItem('casa_lat')) {
                    document.getElementById('combo_boton_' + or_des).innerHTML += `
                    <div class="w3-theme-l4 w3-padding w3-card w3-round-large" onclick="mover${or_des=='origen'?'A':'B'}acasa()"><i class="fas fa-home"></i> Mover el punto ${or_des=='origen'?'A':'B'} a su Casa</div>
                    `;
                }
                if (localStorage.getItem('trabajo_lon') && localStorage.getItem('trabajo_lat')) {
                    document.getElementById('combo_boton_' + or_des).innerHTML += `
                    <div class="w3-theme-l4 w3-padding w3-card w3-round-large" onclick="mover${or_des=='origen'?'A':'B'}atrabajo()"><i class="fas fa-building"></i> Mover el punto ${or_des=='origen'?'A':'B'} a su Trabajo</div>
                    `;
                }

                document.getElementById('combo_boton_' + or_des).innerHTML += `
                <div class="w3-theme-l4 w3-padding w3-card w3-round-large" onclick="${or_des=='origen'?'A':'B'}casa()">Establece la Posicion A como su Casa</div>
                <div class="w3-theme-l4 w3-padding w3-card w3-round-large" onclick="${or_des=='origen'?'A':'B'}trabajo()">Establece la Posicion A como su Trabajo</div>
                `;
            }
            document.getElementById('combo_boton_' + or_des).style.display = 'block';
        }

        const acasa = ()=>{
            if (typeof(Storage) !== "undefined") {
                a = confirm("Confirma establecer la Posicion A como su Casa");
                if (a) {
                    localStorage.setItem('casa_lon', origen.getGeometry().getCoordinates()[0]);
                    localStorage.setItem('casa_lat', origen.getGeometry().getCoordinates()[1]);
                }

            }
        }

        tarifa1 = '';
        tarifa2 = '';
        if (new URL(window.location.href).searchParams.get("t") == 1) {
            tarifa1 = `<div style="position:absolute; top:0px; left:20px;" class="w3-row w3-round-xlarge w3-small w3-red"><span >&nbsp; $43.00 &nbsp;</span></div>`;
            tarifa2 = `<div style="position:absolute; top:0px; left:20px;" class="w3-row w3-round-xlarge w3-small w3-red"><span >&nbsp; $86.00 &nbsp;</span></div>`;
        }

        document.getElementById('intercambiar').addEventListener('click', ()=>{
            document.getElementById('indicaciones_panel').style.display = 'none';
            document.getElementById('indicaciones').innerHTML = '';
            Caminatas.clear();
            Paradas.clear();
            Lineas.clear();
            Desvios.clear();
            Coches.clear();
            Banderas.clear();
            document.getElementById('lineas').innerHTML = '';

            coordenadas_del_origen = origen.getGeometry().getCoordinates();
            coordenadas_del_destino = destino.getGeometry().getCoordinates();

            origen.getGeometry().setCoordinates(coordenadas_del_destino);
            destino.getGeometry().setCoordinates(coordenadas_del_origen);

            texto_origen = document.getElementById('input_origen').value;
            texto_destino = document.getElementById('input_destino').value;
            document.getElementById('input_origen').value = texto_destino;
            document.getElementById('input_destino').value = texto_origen;
        });


        function selecciona_dia(dia) {
            if (dia == 'lv' || dia == 'ls') {
                dia = 'lu';
            }
            if (dia == 'df') {
                dia = 'do';
            }
            document.getElementById('contenido').innerHTML = '';
            
            horario[dia].forEach(hora => {
                document.getElementById('contenido').innerHTML += `<div class="w3-center w3-row">&#8888; ${hora}</div>`;
            });
            document.getElementById('contenido').innerHTML += '<br><br><br><br><br><br><br>';

        }
        var horario = [];
        
        function ver_horarios(codigo, linea, nombre, cliente, sentido) {
            if (sentido == ''){
                sentido = 'I';
            }


            var url = `urbano2_cmd.php?cmd=ver_horarios`;
            
            param = {
                linea:linea,
                codigo:codigo,
                cliente:cliente,
                sentido:sentido,
            }
            if (conf){
                param['conf'] = conf;
            }
            
            nfetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(param),
                timeout: fetchTimeout,
            }).then(a=>{
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data=>{
                    const horario = data;

                    contenidos = document.getElementsByClassName('cont_');
                    contenidos =  Array.prototype.slice.call(contenidos, 0);
                    contenidos.forEach(cont=>{
                        cont.innerHTML = '';
                    });

                    document.getElementById('nombre_parada').innerHTML = nombre + ' - ' + horario.linea_nombre;


                    
                    
                    a='<div class="w3-center w3-row" style="font-weight:normal;">&#8888;';
                    b='<div class="w3-center w3-row" style="font-weight:bold; font-size:18px" id="nn">&#8888;';
                    var fecha = new Date();
                    weekday = fecha.getDay();

                    function hora_now() {
                        var d = new Date();
                        var h = addZero(d.getHours());
                        var m = addZero(d.getMinutes());
                        var s = addZero(d.getSeconds());
                        return h + ":" + m;
                    }

                    function addZero(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                            return i;
                        }

                    if (weekday == 0) {
                        document.getElementById('do').checked = true;
                        
                        document.getElementById('label_do').style.color = 'white';
                        document.getElementById('label_do').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_do').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 1) {
                        document.getElementById('lu').checked = true;
                        
                        document.getElementById('label_lu').style.color = 'white';
                        document.getElementById('label_lu').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_lu').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 2) {
                        document.getElementById('ma').checked = true;
                        
                        document.getElementById('label_ma').style.color = 'white';
                        document.getElementById('label_ma').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_ma').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 3) {
                        document.getElementById('mi').checked = true;
                        
                        document.getElementById('label_mi').style.color = 'white';
                        document.getElementById('label_mi').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_mi').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 4) {
                        document.getElementById('ju').checked = true;
                        
                        document.getElementById('label_ju').style.color = 'white';
                        document.getElementById('label_ju').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_ju').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 5) {
                        document.getElementById('vi').checked = true;
                        
                        document.getElementById('label_vi').style.color = 'white';
                        document.getElementById('label_vi').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_vi').style.border= 'solid 3px #074b83';
                    }
                    if (weekday == 6) {
                        document.getElementById('sa').checked = true;
                        
                        document.getElementById('label_sa').style.color = 'white';
                        document.getElementById('label_sa').style.backgroundColor= '#074b83';
                        document.getElementById('solapa_sa').style.border= 'solid 3px #074b83';
                    }

                    if (horario.lu.toString() == horario.ma.toString() && horario.lu.toString() == horario.mi.toString() &&
                        horario.lu.toString() == horario.ju.toString() && horario.lu.toString() == horario.vi.toString()) {
                        document.getElementById('solapa_lu').style.display = 'none';
                        document.getElementById('solapa_ma').style.display = 'none';
                        document.getElementById('solapa_mi').style.display = 'none';
                        document.getElementById('solapa_ju').style.display = 'none';
                        document.getElementById('solapa_vi').style.display = 'none';
                        document.getElementById('solapa_ls').style.display = 'none';
                        document.getElementById('solapa_lv').style.display = 'block';
                        document.getElementById('solapa_lv').style.fontWeight = 'bold';
                        document.getElementById('solapa_sa').style.display = 'block';
                        document.getElementById('solapa_sa').style.fontWeight = 'bold';

                        // ******************************************
                        document.getElementById('cont_lv').innerHTML = '';
                        flag = true;
                        horario['lu'].forEach(hora => {
                            if (hora > hora_now() && flag && ([1,2,3,4,5]).includes(weekday)) {
                                document.getElementById('cont_lv').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_lv').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_lv').innerHTML += '<br><br><br><br><br><br><br>';
                        
                        document.getElementById('cont_sa').innerHTML = '';

                        flag = true;
                        horario['sa'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==6) {
                                document.getElementById('cont_sa').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_sa').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_sa').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************

                        if (document.getElementById('lu').checked || document.getElementById('ma').checked || document.getElementById('mi').checked ||
                            document.getElementById('ju').checked || document.getElementById('vi').checked) {
                                
                            document.getElementById('lv').checked = true;
                            
                            document.getElementById('label_lv').style.color = 'white';
                            document.getElementById('label_lv').style.backgroundColor= '#074b83';
                            document.getElementById('solapa_lv').style.border= 'solid 3px #074b83';
                            // ******************************************

                            setTimeout(() => {
                                if (document.getElementById('nn')){
                                    document.getElementById('nn').scrollIntoView({behavior: "smooth"});
                                }
                            }, 1500); 
                            // ******************************************

                        }

                        if (horario.lu.toString() == horario.sa.toString()) {

                            document.getElementById('solapa_lv').style.display = 'none';
                            document.getElementById('solapa_sa').style.display = 'none';
                            document.getElementById('solapa_ls').style.display = 'block';
                            document.getElementById('solapa_ls').style.fontWeight = 'bold';
                            
                            if (document.getElementById('lv').checked || document.getElementById('sa').checked) {
                                document.getElementById('ls').checked = true;
                                document.getElementById('label_ls').style.color = 'white';
                                document.getElementById('label_ls').style.backgroundColor= '#074b83';
                                document.getElementById('solapa_ls').style.border= 'solid 3px #074b83';
                                // ******************************************
                                document.getElementById('cont_ls').innerHTML = '';
                                flag = true;
                                horario['lu'].forEach(hora => {
                                    if (hora > hora_now() && flag && ([1,2,3,4,5,6]).includes(weekday)) {
                                        document.getElementById('cont_ls').innerHTML += `${b}${hora}</div>`;
                                        flag = false;
                                    } else {
                                        document.getElementById('cont_ls').innerHTML += `${a}${hora}</div>`;
                                    }
                                });
                                document.getElementById('cont_ls').innerHTML += '<br><br><br><br><br><br><br>';
                                // ******************************************
                            }
                        }
                    } else {
                        document.getElementById('solapa_lu').style.display = 'block';
                        document.getElementById('solapa_lu').style.fontWeight = 'bold';
                        document.getElementById('solapa_ma').style.display = 'block';
                        document.getElementById('solapa_ma').style.fontWeight = 'bold';
                        document.getElementById('solapa_mi').style.display = 'block';
                        document.getElementById('solapa_mi').style.fontWeight = 'bold';
                        document.getElementById('solapa_ju').style.display = 'block';
                        document.getElementById('solapa_ju').style.fontWeight = 'bold';
                        document.getElementById('solapa_vi').style.display = 'block';
                        document.getElementById('solapa_vi').style.fontWeight = 'bold';
                        document.getElementById('solapa_sa').style.display = 'block';
                        document.getElementById('solapa_sa').style.fontWeight = 'bold';                                                                                                                       document.getElementById('solapa_sa').style.display = 'block';
                        document.getElementById('solapa_lv').style.display = 'none';
                        document.getElementById('solapa_ls').style.display = 'none';
                        // ******************************************
                        document.getElementById('cont_lu').innerHTML = '';
                        flag = true;
                        horario['lu'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==1) {
                                document.getElementById('cont_lu').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_lu').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_lu').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                        // ******************************************
                        document.getElementById('cont_ma').innerHTML = '';
                        flag = true;
                        horario['ma'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==2) {
                                document.getElementById('cont_ma').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_ma').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_ma').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                        // ******************************************
                        document.getElementById('cont_mi').innerHTML = '';
                        flag = true;
                        horario['mi'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==3) {
                                document.getElementById('cont_mi').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_mi').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_mi').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                        // ******************************************
                        document.getElementById('cont_ju').innerHTML = '';
                        flag = true;
                        horario['ju'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==4) {
                                document.getElementById('cont_ju').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_ju').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_ju').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                        // ******************************************
                        document.getElementById('cont_vi').innerHTML = '';
                        flag = true;
                        horario['vi'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==5) {
                                document.getElementById('cont_vi').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_vi').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_vi').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                        // ******************************************
                        document.getElementById('cont_sa').innerHTML = '';
                        flag = true;
                        horario['sa'].forEach(hora => {
                            if (hora > hora_now() && flag && weekday==6) {
                                document.getElementById('cont_sa').innerHTML += `${b}${hora}</div>`;
                                flag = false;
                            } else {
                                document.getElementById('cont_sa').innerHTML += `${a}${hora}</div>`;
                            }
                        });
                        document.getElementById('cont_sa').innerHTML += '<br><br><br><br><br><br><br>';
                        // ******************************************
                    }
                    if (horario.do.toString() == horario.fe.toString()) {

                        if (horario.do.length > 0){
                            document.getElementById('solapa_do').style.display = 'none';
                            document.getElementById('solapa_fe').style.display = 'none';
                            document.getElementById('solapa_df').style.display = 'block';
                            document.getElementById('solapa_df').style.fontWeight = 'bold';
                            if (document.getElementById('do').checked || document.getElementById('fe').checked) {
                                document.getElementById('df').checked = true;
                                document.getElementById('label_df').style.color = 'white';
                                document.getElementById('label_df').style.backgroundColor= '#074b83';
                                document.getElementById('solapa_df').style.border= 'solid 3px #074b83';
                            }
                            // ******************************************
                            document.getElementById('cont_df').innerHTML = '';
                            flag = true;
                            horario['do'].forEach(hora => {
                                if (hora > hora_now() && flag && weekday==0) {
                                    document.getElementById('cont_df').innerHTML += `${b}${hora}</div>`;
                                    flag = false;
                                } else {
                                    document.getElementById('cont_df').innerHTML += `${a}${hora}</div>`;
                                }
                            });
                            document.getElementById('cont_df').innerHTML += '<br><br><br><br><br><br><br>';
                            // ******************************************
                        }
                        document.getElementById('solapa_do').style.display = 'none';
                        document.getElementById('solapa_fe').style.display = 'none';
                        document.getElementById('solapa_df').style.display = 'block';
                        document.getElementById('solapa_do').style.fontWeight = 'bold';

                    } else {
                        document.getElementById('solapa_df').style.display = 'none';
                        
                        
                        if (horario['do'].length > 0) {
                            document.getElementById('solapa_do').style.display = 'block';
                            document.getElementById('solapa_do').style.fontWeight = 'bold';
                            // ******************************************
                            document.getElementById('cont_do').innerHTML = '';
                            flag = true;
                            horario['do'].forEach(hora => {
                                if (hora > hora_now() && flag && weekday==0) {
                                    document.getElementById('cont_do').innerHTML += `${b}${hora}</div>`;
                                    flag = false;
                                } else {
                                    document.getElementById('cont_do').innerHTML += `${a}${hora}</div>`;
                                }
                            });
                            document.getElementById('cont_do').innerHTML += '<br><br><br><br><br><br><br>';
                            // ******************************************
                        } else {
                            document.getElementById('solapa_do').style.display = 'none';
                        }
                        if (horario['fe'].length > 0) {
                            document.getElementById('solapa_fe').style.display = 'block';
                            document.getElementById('solapa_fe').style.fontWeight = 'bold';
                            // ******************************************
                            document.getElementById('cont_fe').innerHTML = '';
                            horario['fe'].forEach(hora => {
                                document.getElementById('cont_fe').innerHTML += `${a}${hora}</div>`;
                            });
                            document.getElementById('cont_fe').innerHTML += '<br><br><br><br><br><br><br>';
                            // ******************************************
                        } else{
                            document.getElementById('solapa_fe').style.display = 'none';
                        }
                        
                    }
                    document.getElementById('horario').style.zIndex = 2;
                    document.getElementById('horario').style.display = 'block';
                    document.getElementById('proxarrib').style.overflow = 'hidden';
                    
            });
        }

        const resultadoBusqueda = (ori_des,data)=>{
            document.getElementById('combo_'+ori_des).innerHTML = '';
            const direcciones = [];
            const respuesta = [];
            data.forEach(direccion => {
                const dir=(direccion.display_name.split(",")[1]?direccion.display_name.split(",")[1]:'') +' '
                    + (direccion.display_name.split(",")[0]?direccion.display_name.split(",")[0]:'');
                const dirp= dir.replace(/'/g, "\\'");
                if (direcciones.indexOf(dir) == -1) {
                    direcciones.push(dir);
                    const lon = parseFloat(direccion.lon);
                    const lat = parseFloat(direccion.lat);

                    respuesta.push({
                        'ori_des':ori_des,
                        'dir':dir,
                        'dirp':dirp,
                        'lon':lon,
                        'lat':lat
                    });
                }
            });

            return respuesta;

        }

        var timer;
        const callebusqueda = (ori_des)=>{
            clearTimeout(timer);
            timer = setTimeout( ()=>{
                consulta = document.getElementById(ori_des).value;
                if(consulta.length > 3){
                    var url = `urbano2_cmd.php?a=&cmd=buscar_direccion&q=${consulta}${conf?'&conf='+conf:''}`;
                    nfetch (url,{timeout: fetchTimeout,}).then(a=>a.json()).then(data=>{
                        if (data.length == 0) {
                            parametro = ',cordoba';
                            var url = `https://nominatim.openstreetmap.org/search?&q=${consulta}${parametro}&viewbox=${contexto.min_lon},${contexto.min_lat},${contexto.max_lon},${contexto.max_lat}&bounded=1&polygon=1&format=json&limit=5&addressdetails=1&countrycodes=AR&accept-language=es-AR`;
                            nfetch(url,{timeout: 10000}).then(a => a.json()).then(data=>{
                                resultadoBusqueda(ori_des,data).forEach(show_dir);
                            }).catch(error => {
                            });
                        } else {
                            resultadoBusqueda(ori_des,data).forEach(show_dir);
                        }
                    })
                    .catch(error => {
                    });
                } else {
                    document.getElementById('combo_'+ori_des).innerHTML = '';
                }
            }, 600);
        }

        const show_dir = dir=>{
            if (dir.ori_des == 'direcFavorita'){
                document.getElementById('combo_'+ dir.ori_des).innerHTML += `<div style="
                display: flex;flex-direction: row;" onclick="posicionarOrigenDestino('${dir.ori_des}','${dir.dirp}',${dir.lon},${dir.lat})">
                    <img id="localiza_origen" src="https://cdn-icons-png.flaticon.com/512/186/186250.png" alt="" style="
                        width:20px;
                        height:20px;
                        margin-top: auto;
                        margin-bottom: auto;">
                    <div style="margin:10px">
                        ${dir.dir}
                    </div>
                </div>`;
            } else { 
                document.getElementById('combo_'+ dir.ori_des).innerHTML += `<div style="
                display: flex;flex-direction: row;" onclick="dialogoBuscar.close();posicionarOrigenDestino('${dir.ori_des}','${dir.dirp}',${dir.lon},${dir.lat})">
                    <img id="localiza_origen" src="https://cdn-icons-png.flaticon.com/512/186/186250.png" alt="" style="
                        width:20px;
                        height:20px;
                        margin-top: auto;
                        margin-bottom: auto;">
                    <div style="margin:10px">
                        ${dir.dir}
                    </div>
                </div>`;
            }
            
        }

        function descripcion_calle(descripcion) {
            if(!descripcion){
                return '';
            }
            a = descripcion.split(",");
            if (a.length > 1){
                if (!isNaN(a[0])) {
                    return a[1] + ' ' + a[0];
                } else {
                    return  a[0] + ' ' + a[1];
                }
            } else {
                return  a[0];
            }
        }

        function posicionarOrigenDestino(b, descripcion, lon, lat) {
            if (b == 'origen') {
                if(origen){
                    origen.getGeometry().setCoordinates(ol.proj.fromLonLat([lon, lat]));

                    
                    document.getElementById('input_origen').value = descripcion_calle(descripcion);

                    document.getElementById("lineas").innerHTML = '';

                    if (typeof(Storage) !== "undefined") {
                        localStorage.setItem("origen"+conf, `{"lat": ${lat} , "lon": ${lon}}`);
                    }
                }
                

            } else if (b == 'destino') {
                if(destino){
                    destino.getGeometry().setCoordinates(ol.proj.fromLonLat([lon, lat]));

                    document.getElementById('input_destino').value = descripcion_calle(descripcion);

                    document.getElementById("lineas").innerHTML = '';

                    if (typeof(Storage) !== "undefined") {
                        localStorage.setItem("destino"+conf, `{"lat": ${lat} , "lon": ${lon}}`);
                    }
                }
                
            } else if (b == 'direcFavorita') {
                const nombreDirecFavorita = document.getElementById('nombreDirecFavorita');
                const direcFavorita = document.getElementById('direcFavorita');
                const input_btnAceptarDirecFavorita = document.getElementById('input_btnAceptarDirecFavorita');
                direcFavorita.value = descripcion;
                direcFavorita.setAttribute('lon', lon);
                direcFavorita.setAttribute('lat', lat);
                if (nombreDirecFavorita.value > '') {
                    input_btnAceptarDirecFavorita.checked = true;    
                }
            }
        }


        dialogoBuscar = new nDialogo({
            width: '500px',
            top: '50px;',
            background : true,
            backgroundCloseable : false,
        });

        

        function focusInput(orides){
                        windowWidth = window.innerWidth;
            dir = document.getElementById('input_'+orides).value;
            dialogoBuscar.open(`
                <div style="background-color: #f9f9f9;
                padding: 5px 10px 5px 0px;
                border-radius: 15px;">
                    <img id="${orides}_icono" src="./img/direccion_${orides=='origen'?'A':'B'}3.png" style="height: 30px; margin-left: 10px;">
                    <input onkeyup="callebusqueda('${orides}')" autocomplete="off" type="text" name="${orides}" id="${orides}" style="
                    border: none;" value="${dir}" placeholder="Ingresa una direccion">
                    <img id="localiza_${orides}" src="https://cdn-icons-png.flaticon.com/512/10263/10263254.png" alt=""
                    style="height: 22px; right: 10px;cursor:pointer;" onclick="a=document.getElementById('${orides}'); a.value=''; a.focus()">
                </div>
                <div style="width:100%;max-height: 200px; overflow:auto;" id="combo_${orides}">
                </div>
                <div style="padding: 7px 17px;
                    background-color: #e22700;
                    color: white;
                    border-radius: 12px;
                    text-align: center;
                    width: fit-content;
                    margin: 20px auto 0 auto;" onclick="dialogoBuscar.close()">
                    Cancelar
                </div>
            `);
            dialogoBuscar.e.style.maxWidth = windowWidth + 'px';
            document.getElementById(orides).focus();
                    }

        


        function toggleMenu(accion) {
            document.getElementById("notificacion").hidden = true;
            if (accion == 'ocultar') {
                document.getElementById('toggle_button').setAttribute('estado', 'minimizado');
                document.getElementById('toggle_button').setAttribute('onclick', 'toggleMenu("mostrar")');
                
                document.getElementById('toggle_button').style.marginTop = '10px';
                document.getElementById('toggle_button').style.float = 'right';

                document.getElementById('toggle_button').firstElementChild.remove();
                document.getElementById('toggle_button').innerHTML = `
                <svg width="49px" height="20px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                    viewBox="0 0 4.74 2.89">
                    <path style="fill:#FFF" d="M2.37 1.62c-0.07,-0.05 -1.3,-1.28 -1.45,-1.44 -0.44,-0.43 -1,-0.03 -0.91,0.45 0.06,0.29 1.65,1.77 1.96,2.07 0.25,0.25 0.55,0.25 0.79,0 0.29,-0.28 1.91,-1.78 1.96,-2.07 0.07,-0.35 -0.2,-0.63 -0.51,-0.63 -0.51,0.01 -1.39,1.35 -1.84,1.62z"/>
                </svg>`;

                                                document.getElementById('panel_principal').style.minHeight = '0px';
                 
                
                document.getElementById('map').style.marginTop = '0px';
                document.getElementById('map').style.height = (document.height - 40).toString() + 'px';
                
                map.updateSize();
            } else {
                vistaDeLineas = false;
                clearInterval(ini_coches_ruta);
                
                origen_destino_inicial();
                if (origen && destino){
                    var lon1 = origen.getGeometry().getCoordinates()[0];
                    var lat1 = origen.getGeometry().getCoordinates()[1];
                    var lon2 = destino.getGeometry().getCoordinates()[0];
                    var lat2 = destino.getGeometry().getCoordinates()[1];
                }
                
                document.getElementById('lineas').style.display = 'inline-flex';
                				if(document.getElementById('ver_lineas_btn')){
					document.getElementById('ver_lineas_btn').style.display = 'inline-block';
				}
                                
                

                document.getElementById('toggle_button').setAttribute('estado', 'maximizado');
                document.getElementById('toggle_button').setAttribute('onclick', 'toggleMenu("ocultar")');
                
                document.getElementById('toggle_button').style.marginTop = '-20px';
                document.getElementById('toggle_button').style.float = 'right';

                document.getElementById('toggle_button').firstElementChild.remove();
                document.getElementById('toggle_button').innerHTML = `
                <svg width="49px" height="20px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                    viewBox="0 0 4.74 2.89">
                    <path style="fill:#FFF" d="M2.38 1.28c-0.07,0.04 -1.3,1.29 -1.46,1.44 -0.43,0.43 -1,0.03 -0.91,-0.44 0.06,-0.3 1.66,-1.78 1.97,-2.09 0.25,-0.25 0.55,-0.25 0.8,0 0.29,0.29 1.91,1.79 1.97,2.09 0.07,0.35 -0.21,0.63 -0.52,0.62 -0.51,0 -1.4,-1.35 -1.85,-1.62z"/>
                </svg>`;
                
                                
                document.getElementById('map').style.marginTop = '145px';
                document.getElementById('map').style.height = (document.height - 230).toString() + 'px';
                
                map.updateSize();
            }

        }

        

        const hexToRgba = (hex, alpha)=>{
            var c;
            if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                c = hex.substring(1).split('');
                if (c.length == 3) {
                    c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c = '0x' + c.join('');
                return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + alpha + ')';
            }
            return hex;
        }

        

        function dibujaTraza(coordenadas, color, capa){
            
            traza = new ol.Feature({
                geometry: new ol.geom.LineString(coordenadas.map(coord => ol.proj.fromLonLat(coord))),
            });
            if(capa == 0){
                capaDeLineas.getStyle().getStroke().setColor(hexToRgba(color,0.4));
                Lineas.clear();
                Lineas.addFeature(traza);
            } else if (capa == 1) {
                capaDeLineas1.getStyle().getStroke().setColor(hexToRgba(color,0.4));
                Lineas1.clear();
                Lineas1.addFeature(traza);
            } else if (capa == 2) {
                capaDeDesvios.getStyle().getStroke().setColor(hexToRgba('#000000',0.7));
                Desvios.addFeature(traza);
            }
        }

        function dibujaBandera(lon,lat,tipo){

            bandera = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([lon,lat])),
                tipo:'bandera',
            });
            bandera.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    imgSize: [30, 40],
                    anchor: [.1, 1],
                    src: `./img/bandera${tipo}.png`
                })
            }));
            Banderas.addFeature(bandera);
        }


        function dibujaCaminata(lon1,lat1,lon2,lat2,i){

            var url2 = `https://routing.openstreetmap.de/routed-foot/route/v1/driving/${lon1},${lat1};${lon2},${lat2}?overview=false&alternatives=false&steps=true`;
            var http2 = new XMLHttpRequest();
            http2.open("GET", url2)
            http2.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                    var datos = JSON.parse(this.responseText);
                    result = [];

                    if(datos.routes){
                        steps = datos.routes[0].legs[0].steps;
                        duracion = Math.round(datos.routes[0].legs[0].duration / 60) + ' min ';
                    } else {
                        steps = [];
                        duracion = '';
                    }
                    if(document.getElementById('caminata' + i)){
                        document.getElementById('caminata' + i).innerHTML = duracion;
                    }
                    
                    steps.forEach(esquina=>{
                        esquina.intersections.forEach(semi_tramo=>{
                            result.push(semi_tramo.location);

                        })
                    });

                    
                    traza = new ol.Feature({
                        geometry: new ol.geom.LineString(result.map(coord => ol.proj.fromLonLat(coord))),
                    });
                    Caminatas.addFeature(traza);
                    if(result.length > 0){
                        if (distancia(result[0][1],result[0][0],result[result.length -1][1],result[result.length -1][0]) > 80){
                            peaton = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat(result[1])),
                                tipo:'peaton',
                            });

                            peaton.setStyle(new ol.style.Style({
                                image: new ol.style.Icon({
                                    imgSize: [30, 40],
                                    anchor: [.5, 1],
                                    src: `./img/peaton.png`
                                })
                            }));
                        
                            Caminatas.addFeature(peaton);
                        }
                    }
                }
            }
            http2.send();
        }

        

        function distancia(lat1, lon1, lat2, lon2){
            R = 6378.137;
            
            dLat = lat2 * Math.PI / 180 - lat1 * Math.PI / 180;
            dLon = lon2 * Math.PI / 180 - lon1 * Math.PI / 180;
            a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
                
            c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            d = R * c;
            return d * 1000;
        }

        const dibujaParadas = (paradas, color)=>{
                        if (paradas){
                if(paradas.length > 0){
                    
                    paradas.forEach((parada,orden)=>{
                        nueva_parada = new ol.Feature({
                            geometry: new ol.geom.Point(ol.proj.fromLonLat([parada.lon, parada.lat])),
                            tipo: 'parada',
                            nombre: parada.parada_nombre,
                            codigo: parada.codigo,
                            linea: parada.linea,
                            cliente: parada.cliente,
                            orden: orden + 1,
                            sentido: parada.sentido,
                            linea_nombre: parada.nombre,
                        });

                        
                        nueva_parada.setStyle(
                            new ol.style.Style({
                                image: new ol.style.Circle({
                                    radius: 9,
                                    fill: new ol.style.Fill({
                                        color: '#FFFFFF',
                                    }),
                                    stroke: new ol.style.Stroke({
                                        color: color,
                                        width: 4,
                                    })
                                }),
                                text: new ol.style.Text({
                                    text: muestra_orden ? String(orden + 1) : '',
                                    scale: 1.2,
                                    fill: new ol.style.Fill({
                                        color: '#000000',
                                    }),
                                    stroke: new ol.style.Stroke({
                                        color: '#FFFFFF',
                                        width: 0,
                                    }),
                                    offsetY: 0,
                                    offsetX: 0,

                                })
                            })
                        );

                        
                        Paradas.addFeature(nueva_parada);
                    });
                }
            }
        }

        const maximoYMinimo = (traza) => {
            for(let i=0; i<traza.length; i++){
                if(i == 0){
                    lonMax = traza[i][0];
                    latMax = traza[i][1];
                    lonMin = traza[i][0];
                    latMin = traza[i][1];
                } else {
                    if(traza[i][0] > lonMax){
                        lonMax = traza[i][0];
                    }
                    if(traza[i][1] > latMax){
                        latMax = traza[i][1];
                    }
                    if(traza[i][0] < lonMin){
                        lonMin = traza[i][0];
                    }
                    if(traza[i][1] < latMin){
                        latMin = traza[i][1];
                    }
                }
            }
            return {max:[lonMax, latMax], min:[lonMin, latMin]};
        }


        const seleccionaTraza = (cliente,ruta, elemento=null, limpiaCoches = true, noZoom=false)=>{
            var rutas = document.getElementsByClassName('ruta_');
            for(var i = 0; i < rutas.length; i++){
                rutas[i].style.border = '0px solid white';
            }
            if(elemento){
                elemento.style.border = '3px solid #870000';
            }
            if (limpiaCoches){
                Coches.clear();
            }

            vistaDeLineas = true;
            
            var url = `urbano2_cmd.php/?cmd=seleccionatraza`
            var param = {
                ruta: ruta,
                cliente_id: cliente,
                            }
            if (conf){
                param.conf = conf;
            }

            parada_seleccionada = '';
            
            nfetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Device-FlakerParam': window.flakerParam
                },
                body: new URLSearchParams(param),
                timeout: fetchTimeout,
            }).then(a=>{
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data=>{

                document.getElementById('pantalla_gris').style.display = 'none';

                if (data.ok == 'error') {
                    clearTimeout(error);
                    document.getElementById("error_titulo").innerHTML = `<i class="fas fa-sad-tear fa-2x"></i> Error`;
                    document.getElementById("error_texto").innerHTML = `${data.errorMsg}`;
                    document.getElementById("error").hidden = false;
                    error = setTimeout(function() {
                        document.getElementById("error").hidden = true;
                    }, 5000);
                    return false;
                }

                sentido = data.sentido;
                Lineas.clear();
                Desvios.clear();
                Paradas.clear();
                Caminatas.clear();
                Banderas.clear();
                
                trazaSeleccionada = data.traza;
                dibujaTraza(data.traza, data.color, 0);

                data.notificaciones.forEach(notificacion=>{
                    dibujaTraza(notificacion.desvio, '#000000', 2);
                });
                
                dibujaBandera(data.traza[0][0],data.traza[0][1],'verde');
                dibujaBandera(data.traza[data.traza.length-1][0],data.traza[data.traza.length-1][1],'cuadros');

                dibujaParadas(data.paradas, data.color);
                clearInterval(ini_animation);
                
                let coord = [];
                for (i=0; i< Paradas.getFeatures().length ; i++){
                    if (Paradas.getFeatures()[i].values_.orden == 1){
                        coord = [];
                        coord = Paradas.getFeatures()[i].getGeometry().getCoordinates();
                        break;
                    }
                }
                animacion = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat(coord)),
                    curso: 0
                });
                animacion.setStyle(
                    new ol.style.Style({
                        image: new ol.style.Circle({
                            radius: 9,
                            fill: new ol.style.Fill({
                                color: '#FFFFFF',
                            }),
                            stroke: new ol.style.Stroke({
                                color: data.paradas[0].color,
                                width: 4,
                            })
                        })
                    })
                );
                Coches.addFeature(animacion);
                paradas = Paradas.getFeatures().length;

                max_y_min = maximoYMinimo(data.traza);
                if (!noZoom){
                    setTimeout(() => {
                        vista.fit(new ol.geom.LineString([
                            ol.proj.fromLonLat(max_y_min.min),
                            ol.proj.fromLonLat(max_y_min.max)
                        ]), {
                            padding: [200, 80, 80, 180],
                            duration: 1000
                        });    
                    }, 20);
                }

                var elementpopup = document.getElementById('popup');
                elementpopup.innerHTML = ``;
                ParadasSelec.clear();

                document.getElementById("notificacion").hidden = true;
                if(data.notificaciones.length > 0){
                    muestra_notificacines(data.notificaciones);
                }
            });
        }

        const toggleIndicacionesBody = ()=>{
            if (document.getElementById('indicacionesBody')){
                if (document.getElementById('indicacionesBody').style.display == 'none'){
                    document.getElementById('indicacionesBody').style.display = 'block';;
                }else{
                    document.getElementById('indicacionesBody').style.display = 'none';
                }
            }
        }

        

        paradas = 0;
        contador = 0;
        var ini_animation;
        var ini_coches_ruta;

        const seleccionalinea = (linea, cliente_id, duracion, distancia, sentido ='', paradas = '', par_orides = '', ruta = '',rutas='')=>{
            for (let i = 0; i < document.getElementById('lineas').childNodes.length; i++) {
                document.getElementById('lineas').childNodes[i].style.backgroundColor = 'white';
                document.getElementById('lineas').childNodes[i].style.color = 'black';
            }
            document.getElementById("notificacion").hidden = true;
            document.getElementById('linea' + linea).style.backgroundColor = '#009de0'; // 'cornflowerblue';
            document.getElementById('linea' + linea).style.color = 'white';
            lineaSeleccionada = linea;
            rutaSeleccionada = ruta;

            paradaOrigenSeleccionada = paradas.split('-')[0] + '-' + par_orides.split('-')[0]


            clienteSeleccionado = cliente_id;
            clienteDeLinea = cliente_id;
            var lon1 = origen.getGeometry().getCoordinates()[0];
            var lat1 = origen.getGeometry().getCoordinates()[1];
            var lon2 = destino.getGeometry().getCoordinates()[0];
            var lat2 = destino.getGeometry().getCoordinates()[1];
            document.getElementById('pantalla_gris').style.display = 'block';

            [lon1_, lat1_] = ol.proj.toLonLat([lon1, lat1]);
            [lon2_, lat2_] = ol.proj.toLonLat([lon2, lat2]);
            

            var url = `urbano2_cmd.php?cmd=seleccionalinea&linea=${lineaSeleccionada}&ruta=${ruta}&rutas=${rutas}&cliente_id=${clienteSeleccionado}&lon1=${lon1_}&lat1=${lat1_}&lon2=${lon2_}&lat2=${lat2_}&sentido=${sentido}&paradas=${paradas}&par_orides=${par_orides}&a=''${j?'&j='+j:''}${conf?'&conf='+conf:''}`;

            nfetch(url,{timeout: fetchTimeout,}).
            then(a=>a.json()).then(data=>{
                document.getElementById('pantalla_gris').style.display = 'none';
                Coches.clear();
                consutacoches();
                if (data.ok == 'error') {
                    clearTimeout(error);
                    
                    document.getElementById("error_titulo").innerHTML = `<i class="fas fa-sad-tear fa-2x"></i> Error`;
                    document.getElementById("error_texto").innerHTML = `${data.errorMsg}`;
                    document.getElementById("error").hidden = false;
                    error = setTimeout(function() {
                        document.getElementById("error").hidden = true;
                    }, 5000);
                    
                    return false;
                }

                
                data.caminata.forEach((camino,i)=>{
                    dibujaCaminata(camino[0][0],camino[0][1],camino[1][0],camino[1][1],i);
                });

                
                Banderas.clear();
                dibujaBandera(data.caminata[0][1][0],data.caminata[0][1][1],'verde');
                dibujaBandera(data.caminata[1][0][0],data.caminata[1][0][1],'cuadros');
                
                sentido = data.sentido;
                toggleMenu('ocultar');

                dibujaTraza(data.traza, data.color, 0);
                dibujaTraza(data.traza1, data.color1, 1);

                Paradas.clear();
                Caminatas.clear();

                dibujaParadas(data.paradas, data.color);
                dibujaParadas(data.paradas1, data.color1);


                Desvios.clear();
                if (data.notificaciones){
                    data.notificaciones.forEach(notificacion=>{
                        dibujaTraza(notificacion.desvio, '#000000', 2);
                    });
                }
                

                document.getElementById('indicaciones_panel').style.display = 'block';

                
                if (distancia == 'undefined' || duracion == 'undefined'){
                    header = ``;
                } else {
                    header =`<div style="background-color: #009ee2; color: white;width: 100%;text-align: center; border-radius: 20px 20px 0 0;
                    font-size: 16px;">
                        Viaje de ${distancia} y ${duracion} de duraci&oacute;n
                    </div>`;
                }
                linea_nombre = data.paradas[0].nombre;
                if (data.paradas1[0]){
                    linea_nombre2 = data.paradas1[0].nombre;
                }
                if (data.indicaciones.length == 2) {
                    document.getElementById('indicaciones').innerHTML = `
                    ${header}
                    <div id="indicacionesBody" style="display:block;background-color: var(--primary-bg-color);color: var(--primary-font-color);
                    font-size: 16px; text-align: center; border-radius: 0 0 20px 20px;">
                        <div onclick="popup_parada('${data.indicaciones[0]['parada_nombre']}','${data.indicaciones[0]['codigo']}',${data.indicaciones[0]['linea']},[${data.indicaciones[0]['lon']},${data.indicaciones[0]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[0]['sentido']}','${linea_nombre}')">
                            <div>Caminar <label id="caminata0"> </label>hasta ${data.indicaciones[0]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[1]['parada_nombre']}','${data.indicaciones[1]['codigo']}',${data.indicaciones[1]['linea']},[${data.indicaciones[1]['lon']},${data.indicaciones[1]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[1]['sentido']}','${linea_nombre}')">
                            <div>Bajarse en ${data.indicaciones[1]['parada_nombre']}</div>
                        </div>
                    </div>
                    `;
                } else if(data.indicaciones.length == 3) {
                    document.getElementById('indicaciones').innerHTML = `
                    ${header}
                    <div id="indicacionesBody" style="display:block;background-color: var(--primary-bg-color);color: var(--primary-font-color);
                    font-size: 16px; text-align: center; border-radius: 0 0 20px 20px;">
                        <div onclick="popup_parada('${data.indicaciones[0]['parada_nombre']}','${data.indicaciones[0]['codigo']}',${data.indicaciones[0]['linea']},[${data.indicaciones[0]['lon']},${data.indicaciones[0]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[0]['sentido']}','${linea_nombre}')">
                            <div>Caminar <label id="caminata0"> </label> hasta ${data.indicaciones[0]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[1]['parada_nombre']}','${data.indicaciones[1]['codigo']}',${data.indicaciones[1]['linea']},[${data.indicaciones[1]['lon']},${data.indicaciones[1]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[1]['sentido']}','${linea_nombre2}')">
                            <div>Hacer Trasbordo en ${data.indicaciones[1]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[2]['parada_nombre']}','${data.indicaciones[2]['codigo']}',${data.indicaciones[2]['linea']},[${data.indicaciones[2]['lon']},${data.indicaciones[2]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[2]['sentido']}','${linea_nombre2}')">
                            <div>Bajarse en ${data.indicaciones[2]['parada_nombre']}</div>
                        </div>
                    </div>
                    `;
                } else if(data.indicaciones.length == 4) {
                    document.getElementById('indicaciones').innerHTML = `
                    ${header}
                    <div id="indicacionesBody" style="display:block;background-color: var(--primary-bg-color);color: var(--primary-font-color);
                    font-size: 16px; text-align: center; border-radius: 0 0 20px 20px;">
                        <div onclick="popup_parada('${data.indicaciones[0]['parada_nombre']}','${data.indicaciones[0]['codigo']}',${data.indicaciones[0]['linea']},[${data.indicaciones[0]['lon']},${data.indicaciones[0]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[0]['sentido']}','${linea_nombre}')">
                            <div>Caminar <label id="caminata0"> </label> hasta ${data.indicaciones[0]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[1]['parada_nombre']}','${data.indicaciones[1]['codigo']}',${data.indicaciones[1]['linea']},[${data.indicaciones[1]['lon']},${data.indicaciones[1]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[1]['sentido']}','${linea_nombre}')">
                            <div>Bajarse en ${data.indicaciones[1]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[2]['parada_nombre']}','${data.indicaciones[2]['codigo']}',${data.indicaciones[2]['linea']},[${data.indicaciones[2]['lon']},${data.indicaciones[2]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[2]['sentido']}','${linea_nombre2}')">
                            <div>Caminar hasta ${data.indicaciones[2]['parada_nombre']}</div>
                        </div>
                        <div onclick="popup_parada('${data.indicaciones[3]['parada_nombre']}','${data.indicaciones[3]['codigo']}',${data.indicaciones[3]['linea']},[${data.indicaciones[3]['lon']},${data.indicaciones[3]['lat']}],'si',${cliente_id},'parada','${data.indicaciones[3]['sentido']}','${linea_nombre2}')">
                            <div>Bajarse en ${data.indicaciones[3]['parada_nombre']}</div>
                        </div>
                    </div>
                    `;
                }



                                if (data.notificaciones){
                    if(data.notificaciones.length > 0){
                        muestra_notificacines(data.notificaciones);
                    }
                }
                

                setTimeout(() => {
                    if (document.getElementById('linea'+linea).getAttribute('coche') > 0){
                        if (parseFloat(document.getElementById('linea'+linea).getAttribute('c_lon')) != parseFloat(document.getElementById('linea'+linea).getAttribute('p_lon')) || 
                        parseFloat(document.getElementById('linea'+linea).getAttribute('c_lat')) != parseFloat(document.getElementById('linea'+linea).getAttribute('c_lat')) ){
                            if (document.getElementById('linea'+linea).getAttribute('c_lon') &&
                            document.getElementById('linea'+linea).getAttribute('c_lat') &&
                            document.getElementById('linea'+linea).getAttribute('p_lon') &&
                            document.getElementById('linea'+linea).getAttribute('p_lat')){
                                vista.fit(new ol.geom.LineString([
                                    ol.proj.fromLonLat([parseFloat(document.getElementById('linea'+linea).getAttribute('c_lon')), parseFloat(document.getElementById('linea'+linea).getAttribute('c_lat'))]),
                                    ol.proj.fromLonLat([parseFloat(document.getElementById('linea'+linea).getAttribute('p_lon')), parseFloat(document.getElementById('linea'+linea).getAttribute('p_lat'))])
                                ]), {
                                    padding: [200, 80, 80, 80],
                                    duration: 1000
                                });
                            }
                        }
                    } else {
                        if(data.indicaciones.length > 1){

                            document.getElementById('indicaciones').firstChild.nextSibling.nextSibling.nextSibling.click();
                            setTimeout(() => {
                                const a = map.getSize();
                                map.getView().centerOn(
                                    ol.proj.fromLonLat([parseFloat(data.paradas[0]['lon']),parseFloat(data.paradas[0]['lat'])]),
                                    a,
                                    [a[0]/2, a[1]/2]
                                );
                                map.getView().setZoom(14);
                            }, 1000);
                            
                        } 
                    }
                    
                }, 1000);
                

                var elementpopup = document.getElementById('popup');
                elementpopup.innerHTML = ``;
                ParadasSelec.clear();
            }).catch(function(error) {
                document.getElementById('pantalla_gris').style.display = 'none';
                if (error instanceof DOMException && error.name === 'AbortError') {
                    console.log('timeout');
                } else {
                    console.log('error', error);
                }
            });

        }

        const muestra_notificacines = (notificaciones)=>{
            document.getElementById("notificacion_texto").innerHTML = '';
            tot = notificaciones.length;
            html_notificacion = '';
            for (let i=0; i< tot; i++){
                
                notificacion = notificaciones[i];
                                html_notificacion = '';
                document.getElementById("notificacion_texto").style.flexDirection = 'column';
                html_notificacion += `
                <div style="flex-direction: row; flex-wrap: nowrap; font-size: 12px; display: flex;">
                    <div>
                        <div style="font-weight: bold">${notificacion.titulo}</div>
                        <div>${notificacion.texto}</div>
                    </div>`;
                if (notificacion.icono){
                    html_notificacion += `
                    <div style="display: flex; align-items: center;">
                        <img src="../web/urbano/img/icons/all/${notificacion.icono}" style="width:45px; height:45px;">
                    </div>
                    `;
                }
                html_notificacion += `
                </div>
                `;
                document.getElementById("notificacion_texto").innerHTML += html_notificacion;
                
                            }
            document.getElementById("notificacion").hidden = false;
        }

        
        
        var contexto = {};
        var origen;
        var destino;
        var lineaSeleccionada;
        var clienteSeleccionado;
        var coche_seleccionado;
        var parada_seleccionada = '';
        var rutaSeleccionada = '';
        var rutas_selected = [];
        var paradaOrigenSeleccionada = '';
        var trazaSeleccionada = null;
        

        class Drag extends ol.interaction.Pointer {
            constructor() {
                super({
                handleDownEvent: handleDownEvent,
                handleDragEvent: handleDragEvent,
                handleMoveEvent: handleMoveEvent,
                handleUpEvent: handleUpEvent,
                });

                this.coordinate_ = null;
                this.cursor_ = 'pointer';
                this.feature_ = null;
                this.previousCursor_ = undefined;
            }
        }
        
        var vista = new ol.View({
            projection: 'EPSG:3857',
            center: ol.proj.fromLonLat([-64.185629, -38.414149]),
            zoom: 4.5,
            minZoom:11,
        })
        
        var Coches = new ol.source.Vector({
            features: []
        });
        
        var capaDeCoches = new ol.layer.Vector({
            source: Coches,
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    color: '#FFFFFF',
                    imgSize: [40, 60],
                    anchor: [0.5, 0.6],
                    displacement: [0, -15],
                    src: './img/colectivo2.png',
                })
            })
        });
        
        var Paradas = new ol.source.Vector({
            features: []
        });

        var capaDeParadas = new ol.layer.Vector({
            source: Paradas
        });
        
        var ParadasSelec = new ol.source.Vector({
            features: []
        });

        var capaDeParadasSelec = new ol.layer.Vector({
            source: ParadasSelec,
            style:new ol.style.Style({
                image: new ol.style.Circle({
                    radius: 9,
                    fill: new ol.style.Fill({
                        color: '#0000FF',
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'rgba(0,0,0,0)',
                        width: 4,
                    })
                })
            })
        });
        

        var Puntos = new ol.source.Vector({
            features: []
        });

        var capaDePuntos = new ol.layer.Vector({
            source: Puntos,
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    color: '#FFFFFF',
                    imgSize: [40, 40],
                    displacement: [-4, -2],
                    src: 'http://maps.google.com/mapfiles/kml/shapes/placemark_circle.png',
                })
            })
        });

        var Banderas = new ol.source.Vector({
            features: []
        });

        var capaDeBanderas = new ol.layer.Vector({
            source: Banderas,
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    color: '#FFFFFF',
                    imgSize: [40, 40],
                    src: 'http://maps.google.com/mapfiles/kml/shapes/placemark_circle.png',
                })
            })
        });

        var Geolocalizacion = new ol.source.Vector({
            features: []
        });

        var capaDeGeolocalizacion = new ol.layer.Vector({
            source: Geolocalizacion,
            style: new ol.style.Style({
                image: new ol.style.Icon({
											scale: 0.07,
						anchor: [.49, 1],
						src: 'https://cdn-icons-png.flaticon.com/512/3603/3603850.png',
					                })
            })
        });

        
        var Caminatas = new ol.source.Vector({
            features: []
        });

        
        var capaDeCaminatas = new ol.layer.Vector({
            source: Caminatas,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    width: 7,
                    lineCap:'round',
                    lineJoin:'round',
                    lineDash:[4,20],
                    color: '#009900',
                }),
            })
        });

        var Desvios = new ol.source.Vector({
            features: []
        });

        var capaDeDesvios = new ol.layer.Vector({
            source: Desvios,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    lineDash: [10,20],
                    width: 7,
                    color: 'rgba(0,0,0,0.4)',
                }),
            })
        });


        var Lineas = new ol.source.Vector({
            features: []
        });

        var capaDeLineas = new ol.layer.Vector({
            source: Lineas,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    width: 7,
                    color: '#ff0000',
                }),
            })
        });

        var Lineas1 = new ol.source.Vector({
            features: []
        });
        var capaDeLineas1 = new ol.layer.Vector({
            source: Lineas1,
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    width: 7,
                    color: '#ff0000',
                }),
            })
        });


		                            var googleMapsLight = new ol.layer.Tile({
                    source: new ol.source.XYZ({
                        url: 'https://mts0.google.com/vt/lyrs=m@221097413&hl=es_AR&src=app&x={x}&y={y}&z={z}&apistyle=s.t:2|s.e:l|p.v:off,s.t:3|s.e:l|p.v:on,s.t:20|s.e:l|p.v:simplified',
                    })
                });

                var googleMapsDark = new ol.layer.Tile({
                    source: new ol.source.XYZ({
                        url: 'https://mts0.google.com/vt/lyrs=m@221097413&hl=es_AR&src=app&x={x}&y={y}&z={z}&apistyle=s.t:0|s.e:l|p.v:off,s.t:3|s.e:l|p.v:on,s.t:20|s.e:l|p.v:simplified,s.t:0|s.e:g|p.c:%23ff263c3f,s.t:3|s.e:g|p.c:%23ff38414e,s.t:5|s.e:g|p.c:%23ff242f3e,s.t:0|s.e:l.t.s|p.c:%23ff000000,s.t:1|s.e:l.t.f|p.c:%23ff9ba3b3,s.t:1|s.e:l.t.s|p.c:%23ff38414e,s.t:3|s.e:l.t.f|p.c:%23ff9ba3b3,s.t:3|s.e:l.t.s|p.c:%23ff38414e,s.t:6|s.e:g|p.c:%23ff17263c',
                    })
                });

                var actualMap = googleMapsLight;

                if (localStorage.getItem('dark-mode_cbaciudad') === 'true') {
                    actualMap = googleMapsDark
                    document.body.classList.add('dark');
                }

                function set_theme_mode() {
                    if (actualMap == googleMapsLight) {
                        map.removeLayer(googleMapsLight);
                        googleMapsDark.setZIndex(-1);
                        map.addLayer(googleMapsDark);
                        actualMap = googleMapsDark
                    } else {
                        map.removeLayer(googleMapsDark);
                        googleMapsLight.setZIndex(-1);
                        map.addLayer(googleMapsLight);
                        actualMap = googleMapsLight
                    }
                }
            

		
		const originalGetContext = HTMLCanvasElement.prototype.getContext;
        HTMLCanvasElement.prototype.getContext = function(type, options) {
            if (type === '2d') {
                options = Object.assign({}, options, { willReadFrequently: true });
            }
            return originalGetContext.call(this, type, options);
        };

        var map = new ol.Map({
            interactions: ol.interaction.defaults.defaults({altShiftDragRotate:false, pinchRotate:false}).extend([new Drag()]),
            target: 'map',
            pixelRatio: 1,
            layers: [

                
                actualMap,


                                capaDeDesvios,
                capaDeLineas,
                capaDeLineas1,
                capaDeGeolocalizacion,
                //capaDeEntornos,
                capaDeParadas,
                capaDeParadasSelec,
                capaDeCaminatas,
                capaDeBanderas,
                capaDeCoches,
                capaDePuntos
            ],
            view: vista
        });
        
        let muestra_orden = false;
        
        map.getView().on('change:resolution', (event) => {
            
            if (event.target.values_.zoom > 14.00 && muestra_orden == false) {
                muestra_orden = true;
                Paradas.getFeatures().forEach(function(f) {
                    const estilo = f.getStyle();
                    if(estilo.getText()){
                        estilo.getText().setText(f.values_.orden.toString());
                        f.setStyle(estilo);
                    }
                });
            } else if(event.target.values_.zoom < 15 && muestra_orden == true) {
                muestra_orden = false;
                Paradas.getFeatures().forEach(function(f) {
                    const estilo = f.getStyle();
                    if(estilo.getText()){
                        estilo.getText().setText('');
                        f.setStyle(estilo);
                    }
                });
            }
        });

        function consutacoches() {
            if (document.getElementById('demora_' + lineaSeleccionada)) {

            } else {
                return;
            }
            coches = document.getElementById('demora_' + lineaSeleccionada).getAttribute('coches');
            if (coches == '' || coches === null) {
                return;
            }
            cliente = document.getElementById('demora_' + lineaSeleccionada).getAttribute('cliente');
            color = document.getElementById('demora_' + lineaSeleccionada).getAttribute('color');
            if(color){
                color = color.replace('#', '*');
            }


            var url = `urbano2_cmd.php/?cmd=consultacoche`
            var param = {
                color: color,
                coches: coches,
                cliente: cliente,
                            }
            if (conf){
                param.conf = conf;
            }
            
            nfetch (url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(param),
                timeout: fetchTimeout,
            }).then(a=>{
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data=>{
                dibuja_coches(data);
            }).catch(error=>{
                if (error instanceof DOMException && error.name === 'AbortError') {
                    console.log('timeout');
                } else {
                    console.log('error', error);
                }
            });

        }

        const consutacoches_por_ruta = async (ruta, cliente, coche = 0)=> {

            if(coche > 0){
                valcoche = `&coche=${coche}`;
            } else {
                valcoche = '';
            }

            var url = `urbano2_cmd.php?cmd=consultacocheporruta`;
            var params = {
                ruta: ruta,
                coche: coche>0?coche:0,
                cliente:cliente,
                parada_seleccionada: parada_seleccionada,
                conf:conf,
                operacion:'11543393610633694179',
                            };

            let a = await nfetch(url, {
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Device-FlakerParam': window.flakerParam
                },
                body: new URLSearchParams(params),
                timeout: fetchTimeout,
            });
            let data;

            if (a.status == 408){
                location.reload();
                return;
            }
            if(a.ok){
                data = await a.json();
            }




                        
            coches = data.coches;
            
            if (coches){
                coches.sort(function(a, b) {
                    return a.demora_minutos - b.demora_minutos;
                });
                for(var i=0;i<coches.length;i++){
                    if (coches[i].demora_minutos && coches[i].demora){
                        
                        const par = Paradas.getFeatures().filter(p=>p.values_.codigo==parada_seleccionada);
                        if (par[0]){
                            coord_parada = par[0].getGeometry().getCoordinates();
                        }
                        coord_coche = [coches[i].lon, coches[i].lat];

                        if (document.getElementById('popup_parada_nombre_demora')){
                            document.getElementById('popup_parada_nombre_demora').innerHTML = `<div>(aprox. ${coches[i].demora})</div>`;
                        }
                        break;
                    }
                }
                coches.forEach((coche,i)=>{
                    if(coche.posicion){
                        if(trazaSeleccionada){
                            if (coche.posicion-1 > trazaSeleccionada.length){
                                coche.color = '#007700';
                            } else {
                                var desfasaje = Math.abs(coche.curso - trazaSeleccionada[coche.posicion-1][2]);

                                if (desfasaje > Math.PI) {
                                    desfasaje = (Math.PI*2) - desfasaje;
                                }
                                if (desfasaje > Math.PI/2) {
                                    coches[i].color = '#007700';
                                }
                            }
                            
                        }
                    }
                });
            }

            dibuja_coches(coches, demora=false);
            
            clearTimeout(ini_coches_ruta);
            ini_coches_ruta = setTimeout(function() {
                consutacoches_por_ruta(ruta, cliente, coche);
            }, 15000);

            // });
        }

        var combi = null;

        function animarFeature(feature, coordInicio, coordFin, duracion = 1000, easing = t => 1 - Math.pow(1 - t, 3)) {
            const start = performance.now();
            function animateFrame(timestamp) {
                const elapsed = timestamp - start;
                const t = Math.min(elapsed / duracion, 1);
                const p = easing(t);

                const interpolada = [
                    coordInicio[0] + (coordFin[0] - coordInicio[0]) * p,
                    coordInicio[1] + (coordFin[1] - coordInicio[1]) * p
                ];

                feature.setGeometry(new ol.geom.Point(interpolada));

                if (t < 1) {
                    requestAnimationFrame(animateFrame);
                }
            }
            requestAnimationFrame(animateFrame);
        }

        const dibuja_coches = async (data, demora=true)=>{
            if (demora){

                calcula_demora_de_linea(lineaSeleccionada, clienteSeleccionado, combi,0,0,false);
                
            }


            if(data){
                Coches.getFeatures().forEach(coche => {
                    let encuentra = false;
                    
                    for(let i = 0; i < data.length; i++){
                        if(coche.values_.serie == data[i].serie && coche.values_.cliente == data[i].cliente){
                            encuentra = true;
                                                            const str_curso = '';
                            
                            let rayo = '';
                            if(data[i].coche==2001 && data[i].cliente==421){
                                rayo = '&rayo=1';
                            }

                            coche.setStyle([
                                new ol.style.Style({
                                    image: new ol.style.Icon({
                                        scale: .6, //.6,
                                        anchor: [0.5, 0.6],
                                        displacement: [0, 0],
                                        src: `urbano2_cmd.php?cmd=icono${str_curso}&color=${encodeURIComponent(data[i].color)}&rampa=${data[i].rampa}${rayo}`,
                                        rotate: true
                                    })
                                }),
                                new ol.style.Style({                                    text: new ol.style.Text({
                                        textAlign: 'center',
                                        textBaseline: 'bottom',
                                        font: '18px sans-serif',
                                        text: data[i].demora,
                                        fill: new ol.style.Fill({
                                            color: '#000000'
                                        }),
                                        stroke: new ol.style.Stroke({
                                            color: '#FFFFFF',
                                            width: 4
                                        }),
                                        offsetX: 0,
                                        offsetY: -18,
                                        rotation: 0,
                                        rotate: true
                                    })
                                }),
                                new ol.style.Style({                                    text: new ol.style.Text({
                                        textAlign: 'center',
                                        textBaseline: 'top',
                                        font: '12px sans-serif',
                                        text: data[i].comentario,
                                        fill: new ol.style.Fill({
                                            color: '#000000'
                                        }),
                                        stroke: new ol.style.Stroke({
                                            color: '#FFFFFF',
                                            width: 4
                                        }),
                                        offsetX: 0,
                                        offsetY: 16,
                                        rotation: 0,
                                        rotate: true
                                    })
                                })
                            ]
                                
                            );
                            animarFeature(coche, coche.getGeometry().getCoordinates(), ol.proj.fromLonLat([data[i].lon, data[i].lat]));
                            break;
                        }
                    }
                    if(!encuentra){
                        Coches.removeFeature(coche);
                    }
                });

                const data_add = [];

                data.forEach(coche => {
                    let encuentra = false;
                    for(let i = 0; i < Coches.getFeatures().length; i++){
                        if(coche.serie == Coches.getFeatures()[i].values_.serie){
                            encuentra = true;
                            break;
                        }
                    }
                    if(!encuentra){
                        data_add.push(coche);
                    }
                })



                // Coches.clear();
                data_add.forEach(coche => {
                    if (demora){
                        if (document.getElementById('demora_' + lineaSeleccionada)){
                            coch = JSON.parse(document.getElementById('demora_' + lineaSeleccionada).getAttribute('coches'));
                            prox = JSON.parse(document.getElementById('demora_' + lineaSeleccionada).getAttribute('proximo'));
                            for (i = 0; i < coch.length; i++) {
                                if (coche.coche == coch[i]) {
                                    coche.demora = prox[i];
                                }
                            }
                        }
                    }

                                            const str_curso = '';
                    
                    let rayo = '';
                    if(coche.coche==2001 && coche.cliente==421){
                        rayo = '&rayo=1';
                    }
                    
                    src = `urbano2_cmd.php?cmd=icono${str_curso}&color=${encodeURIComponent(coche.color)}&rampa=${coche.rampa}${rayo}`;
                    color = coche.color ? coche.color : 'white';

                    colectivo = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([coche.lon, coche.lat])),
                        tipo: 'coche',
                        coche: coche.coche,
                        fechayhora: coche.fechayhora,
                        curso: coche.curso,
                        cliente: coche.cliente,
                        rampa: coche.rampa,
                        serie: coche.serie,
                                            });

                    colectivo.setStyle(
                        [
                            new ol.style.Style({
                                image: new ol.style.Icon({
                                    // color: color,
                                    scale: .6, //.6,
                                    anchor: [0.5, 0.6],
                                    displacement: [0, 0],
                                    // rotation: coche.curso,
                                    src: src,
                                    rotate: true
                                })
                            }),
                            new ol.style.Style({                                 text: new ol.style.Text({
                                    textAlign: 'center',
                                    textBaseline: 'bottom',
                                    font: '18px sans-serif',
                                    text: coche.demora,
                                    fill: new ol.style.Fill({
                                        color: '#000000'
                                    }),
                                    stroke: new ol.style.Stroke({
                                        color: '#FFFFFF',
                                        width: 4
                                    }),
                                    offsetX: 0,
                                    offsetY: -18,
                                    rotation: 0,
                                    rotate: true
                                })
                            }),
                            new ol.style.Style({                                text: new ol.style.Text({
                                    textAlign: 'center',
                                    textBaseline: 'top',
                                    font: '12px sans-serif',
                                    text: coche.comentario,
                                    fill: new ol.style.Fill({
                                        color: '#000000'
                                    }),
                                    stroke: new ol.style.Stroke({
                                        color: '#FFFFFF',
                                        width: 4
                                    }),
                                    offsetX: 0,
                                    offsetY: 16,
                                    rotation: 0,
                                    rotate: true
                                })
                            }),
                        ]
                    );

                    Coches.addFeature(colectivo);

                });

            }
            
        }

        
        function encontexto(coordenadas) {

            lon_min = contexto.min_lon;
            lon_max = contexto.max_lon;
            lat_min = contexto.min_lat;
            lat_max = contexto.max_lat;

            if (parseFloat(contexto.min_lon) > parseFloat(contexto.max_lon)) {
                lon_min = contexto.max_lon;
                lon_max = contexto.min_lon;
            }

            if (parseFloat(contexto.min_lat) > parseFloat(contexto.max_lat)) {
                lat_min = contexto.max_lat;
                lat_max = contexto.min_lat;
            }
            if ((lon_min - lon_max) < .1) {
                lon_min = lon_min - .1;
                lon_max = lon_max + .1;
            }

            if ((lat_min - lat_max) < .1) {
                lat_min = lat_min - .1;
                lat_max = lat_max + .1;
            }

            if (coordenadas[0] > lon_min && coordenadas[0] < lon_max && coordenadas[1] > lat_min && coordenadas[1] < lat_max) {

                return true;
            }
            return false;
        }

        

        function milocalizacion(n='', leyenda = '') {
            const obtiene_posicion = position=>{

                var lon = position.coords.longitude;
                var lat = position.coords.latitude;

                

                if (encontexto([lon, lat])) {
                    if (n==''){
                        posicion = {lat:lat,lon:lon};
                    } else {
                        if (n == 'A') {
                            if(origen){
                                origen.getGeometry().setCoordinates(ol.proj.fromLonLat([lon, lat]));

                                carga_direccion_origen_de_coordenada(lon, lat);
                                posicion = {lat:lat,lon:lon};
                                if (leyenda != ''){
                                    setTimeout(()=>{
                                        if (document.getElementById('origen')){
                                            document.getElementById('origen').value = leyenda;
                                        }
                                    },500);
                                }
                            }
                            

                            Geolocalizacion.clear();
                            milocal = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat([lon, lat])),
                                tipo: 'milocal'
                            });
                            Geolocalizacion.addFeature(milocal);

                            
                        } else if (n == 'B') {
                            destino.getGeometry().setCoordinates(ol.proj.fromLonLat([lon, lat]));
                            carga_direccion_destino_de_coordenada(lon, lat);
                            posicion = {lat:lat,lon:lon};

                            Geolocalizacion.clear();
                            milocal = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat([lon, lat])),
                                tipo: 'milocal'
                            });
                            Geolocalizacion.addFeature(milocal);
                        }

                        else if (n == 'X') {
                            Geolocalizacion.clear();
                            milocal = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat([lon, lat])),
                                tipo: 'milocal'
                            });
                            Geolocalizacion.addFeature(milocal);
                        }
                    }
                    

                } else {
                    if (n!=''){
                        setTimeout(function() {
                            content.innerHTML = "";
                        }, 3000);
                    }
                    
                }

            }

            const posicion_error =  objPositionError=>{
                switch (objPositionError.code) {
                    case objPositionError.PERMISSION_DENIED:
                        if (n!=''){
                            setTimeout(function() {
                                content.innerHTML = "";
                            }, 3000);
                        }
                        break;
                    case objPositionError.POSITION_UNAVAILABLE:
                        if (n!=''){
                            setTimeout(function() {
                                content.innerHTML = "";
                            }, 3000);
                        }
                        break;
                    case objPositionError.TIMEOUT:
                        if (n!=''){
                            setTimeout(function() {
                                content.innerHTML = "";
                            }, 10000);
                        }
                        break;
                    default:
                        if (n!=''){
                            setTimeout(function() {
                                content.innerHTML = "";
                            }, 3000);
                        }
                }
            }


            var content = document.getElementById("geolocation_test");
            if (navigator.geolocation) {
                
                if (iniFrame()){
                    window.parent.getCurrentPosition().then(obtiene_posicion, posicion_error , {
                        maximumAge: 75000,
                        timeout: 15000
                    });
                } else {
                    getCurrentPosition().then(obtiene_posicion, posicion_error , {
                        maximumAge: 75000,
                        timeout: 15000
                    });
                }
                
            }
        }

        function handleDownEvent(evt) {
            var map = evt.map;
            var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                return feature;
            });
            if (feature && (feature.values_.tipo == 'parada' || feature.values_.tipo == 'coche' ||
            feature.values_.tipo == 'bandera' || feature.values_.tipo == 'peaton' || feature.values_.tipo == 'milocal')) {
                return false;
            }

            if (feature && feature.getGeometry().flatCoordinates.length == 2) {
            
                this.coordinate_ = evt.coordinate;
                this.feature_ = feature;
            } else {
                return false;
            }

            return !!feature;
        }

        function handleDragEvent(evt) {
            
            var deltaX = evt.coordinate[0] - this.coordinate_[0];
            var deltaY = evt.coordinate[1] - this.coordinate_[1];

            var geometry = this.feature_.getGeometry();
            geometry.translate(deltaX, deltaY);

            this.coordinate_[0] = evt.coordinate[0];
            this.coordinate_[1] = evt.coordinate[1];
            var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                return feature;
            });
        }

        function handleMoveEvent(evt) {}

        function handleUpEvent(evt) {

            var feature = map.forEachFeatureAtPixel(evt.pixel, function(feature) {
                return feature;
            });
            if (feature) {
                const coord_ = feature.getGeometry().getCoordinates();
                const [lon_,lat_] = ol.proj.toLonLat(coord_);
                if (feature.values_.nombre == 'origen') {
                    carga_direccion_origen_de_coordenada(feature.getGeometry().flatCoordinates[0],feature.getGeometry().flatCoordinates[1]); // evt.coordinate[0], evt.coordinate[1]);
                    if (typeof(Storage) !== "undefined") {
                        localStorage.setItem("origen"+conf, `{"lat": ${lat_} , "lon": ${lon_}}`);
                    }
					if(document.getElementById('compartir')){
						document.getElementById('compartir').style.display = 'none';
					}
                }
                if (feature.values_.nombre == 'destino') {
                    carga_direccion_destino_de_coordenada(feature.getGeometry().flatCoordinates[0],feature.getGeometry().flatCoordinates[1]); // evt.coordinate[0], evt.coordinate[1]);
                    if (typeof(Storage) !== "undefined") {
                        localStorage.setItem("destino"+conf, `{"lat": ${lat_} , "lon": ${lon_}}`);
                    }
					if(document.getElementById('compartir')){
						document.getElementById('compartir').style.display = 'none';	
					}
                    
                }

                document.getElementById("lineas").innerHTML = '';


                this.coordinate_ = null;
                this.feature_ = null;
                return false;
            }
        }

        function origen_destino_inicial(coordenadas= false) {
            if(Puntos.getFeatures().length == 0){
                // var url = `urbano2_cmd.php?cmd=origendestino${a?'&a='+a:''}${j?'&j='+j:''}${conf?'&conf='+conf:''}`;
                var url = `urbano2_cmd.php?cmd=origendestino`
                var params = {
                    conf:conf,
                                    };
                if(a){
                    params.a = a;
                }
                if(j){
                    params.j = j;
                }


                var or = false;
                var des = false;
                if (typeof(Storage) !== "undefined") {
                    or = localStorage.getItem("origen"+conf);
                    des = localStorage.getItem("destino"+conf);

                    if (or) {

                        result_origen = JSON.parse(or);
                    }
                    if (des) {
                        result_destino = JSON.parse(des);
                    }
                }
                if (coordenadas){
                    or = true;
                    des = true;
                    result_origen = coordenadas.origen;
                    result_destino = coordenadas.destino;
                }
                

                if (or && des) {
                    origen = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([result_origen.lon, result_origen.lat])),
                        nombre: 'origen',
                    });
                    origen.setStyle(new ol.style.Style({
                        image: new ol.style.Icon({
                            imgSize: [42, 56],
                            anchor: [0.5, 1],
                            src: './img/direccion_A2.png'
                        })
                    }));
                    Puntos.addFeature(origen);
                    destino = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([result_destino.lon, result_destino.lat])),
                        nombre: 'destino',
                    });
                    destino.setStyle(new ol.style.Style({
                        image: new ol.style.Icon({
                            imgSize: [42, 56],
                            anchor: [0.5, 1],
                            src: './img/direccion_B2.png'
                        })
                    }));
                    Puntos.addFeature(destino);
                    carga_direccion_origen_de_coordenada(result_origen.lon, result_origen.lat);
                    carga_direccion_destino_de_coordenada(result_destino.lon, result_destino.lat);
                    return;
                }


                nfetch(url, {
                    method: 'POST',
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(params),
                    timeout: fetchTimeout,
                }).then(a=>{
                    if(a.ok){
                        return a.json();
                    }
                    if (a.status == 408){
                        location.reload();
                    }
                }).then(data=>{
                        if (data != {}) {
                            origen = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat([data.lon_a, data.lat_a])),
                                nombre: 'origen',
                            });
                            origen.setStyle(new ol.style.Style({
                                image: new ol.style.Icon({
                                    imgSize: [42, 56],
                                    anchor: [0.5, 1],
                                    src: './img/direccion_A2.png'
                                })
                            }));
                            Puntos.addFeature(origen);
                            destino = new ol.Feature({
                                geometry: new ol.geom.Point(ol.proj.fromLonLat([data.lon_b, data.lat_b])),
                                nombre: 'destino',
                            });
                            destino.setStyle(new ol.style.Style({
                                image: new ol.style.Icon({
                                    imgSize: [42, 56],
                                    anchor: [0.5, 1],
                                    src: './img/direccion_B2.png'
                                })
                            }));
                            Puntos.addFeature(destino);

                            carga_direccion_origen_de_coordenada(data.lon_a, data.lat_a);
                            carga_direccion_destino_de_coordenada(data.lon_b, data.lat_b);
                        }
                });
            }
        }
        var error;

        var coches_array = [];
        var buscar_lineas = false;
        const buscarlineas = (la1=false,lo1=false,la2=false,lo2=false,e=false)=>{
                            document.getElementById('input_panel_principal').checked = false;
                        rutas_selected = [];

            leyenda_segun_diagrama.style.display = 'none';
            vistaDeLineas = false;
            clearInterval(ini_coches_ruta);

            if(la1 && lo1 && la2 && lo2 && e) {
                
                var lon1 = lo1
                var lat1 = la1;
                var lon2 = lo2;
                var lat2 = la2;
                var entorno = e;
            } else {
                var lon1 = origen.getGeometry().getCoordinates()[0];
                var lat1 = origen.getGeometry().getCoordinates()[1];
                var lon2 = destino.getGeometry().getCoordinates()[0];
                var lat2 = destino.getGeometry().getCoordinates()[1];
            }
            toggleMenu("mostrar");

            buscar_lineas = true;

            if(usuario.email){
                email = usuario.email;
            } else {
                email = '';
            }

            

            document.getElementById('indicaciones_panel').style.display = 'none';
            document.getElementById('indicaciones').innerHTML = '';

            clearTimeout(error);
            if (document.getElementById("error")) {
                document.getElementById("error_titulo").innerHTML = '';
                document.getElementById("error_texto").innerHTML = `No se encontraron recorridos. Aumenta el radio de caminata, o cambia el origen o destino`;
                document.getElementById("error").hidden = true;
            }

            coches_array = [];

            milocalizacion('X');
            document.getElementById("lineas").innerHTML = '';
            iniciarBuscarLineas(lon1,lat1,lon2,lat2,entorno);


            document.getElementById("lineas").innerHTML = '';
			if(document.getElementById('compartir')){
				document.getElementById('compartir').style.display = 'none';
			}
            Lineas.clear();
            Lineas1.clear();
            Desvios.clear();
            Coches.clear();
            Paradas.clear();
            Caminatas.clear();
            Banderas.clear();
            var elementpopup = document.getElementById('popup');
            elementpopup.innerHTML = ``;
            ParadasSelec.clear();
        }

        const esTroncal = (linea, cliente)=>{
            for(let i=0; i<listadoDeGrupos.length; i++){
                if(parseInt(listadoDeGrupos[i].troncal) == parseInt(linea) && parseInt(listadoDeGrupos[i].cliente_id) == parseInt(cliente)){
                    return listadoDeGrupos[i].grupo_id;
                }
            }
            return false;
        }

        const grupoDeLinea = (linea)=>{
            for(let i=0; i<listadoDeLineas.length; i++){
                if(linea = listadoDeLineas[i].linea_id){
                    return listadoDeLineas[i].grupo;
                }
            }
        }

        const ocultaCombinacionesNoTroncales = ()=>{
            lineas = document.getElementById('lineas').childNodes;
            
            troncales = [];
            for (let i=0; i<lineas.length; i++){
                grupo2 = esTroncal(linea2, cliente2);
                if (grupo2){
                    if(!troncales.includes(grupo2+'-'+cliente2)){
                        troncales.push(grupo2+'-'+cliente2);
                    }
                }
            }
            for (let i=0; i<lineas.length; i++){
                linea1 = lineas[i].getAttribute('linea').split(' ')[0].split('-')[0];
                cliente1 = lineas[i].getAttribute('linea').split(' ')[0].split('-')[1];
                if (lineas[i].getAttribute('linea').split(' ')[1]){
                    linea2 = lineas[i].getAttribute('linea').split(' ')[1].split('-')[0];
                    cliente2 = lineas[i].getAttribute('linea').split(' ')[1].split('-')[1];
                }
                

                if (!esTroncal(linea2, cliente2)){
                    
                    if(troncales.includes(grupoDeLinea(linea2)+'-'+cliente2)){
                        lineas[i].style.display = 'none';
                    }
                }
            }
        }

        const nfetch = (url, options)=>{
            const { timeout } = options;
            const controller = new AbortController();
            const timeoutId = setTimeout(() => {
                controller.abort();
            }, timeout);
            return fetch(url, {
                ...options,
                signal: controller.signal
            })
            .finally(() => {
                clearTimeout(timeoutId);
            });
        }

        const iniciarBuscarLineas = (lon1,lat1,lon2,lat2,entorno,resultados = '', continua = 1)=>{
            
            resultados = JSON.stringify(resultados);
            if (continua ==  1){
                msg = 'Buscando Lineas...';
            } else if (continua == 2){
                msg = 'Ampliando Rango de Busqueda...';
            } else if (continua == 3){
                msg = 'Buscando Combinaciones...';
            } else if (continua == 4){
                msg = 'Ampliando Rango de Busqueda...';
            }
            if (document.getElementById('lineas').childNodes.length == 0) {
                document.getElementById('pantalla_gris').style.display = 'block';
                document.getElementById('pantalla_gris_msg').innerHTML = msg;
            } else {
                                document.getElementById('lineas').innerHTML += `<div id="buscandoMsg" linea="" class="w3-container w3-card-4 w3-border w3-round-large buscandoMsg"
                style="display:block; position:relative;font-size:14px position: relative; background-color: rgba(255,255,255,.5);
                text-align: center;
                width:7vw; min-width:110px; max-width:150px;height:90px; padding:1px;">
                Buscando mas resultados...<br>
                <i class="fa fa-spinner w3-spin" style="font-size:24px; margin-top:10px"></i>
                </div>`;
                            }

            
            
            if (typeof(Storage) !== "undefined") {
                userkey = localStorage.getItem("userkey");
            } else {
                userkey = '';
            }

            
            if (Geolocalizacion.getFeatures()[0]){
                usr_lon = Geolocalizacion.getFeatures()[0].getGeometry().getCoordinates()[0];
                usr_lat = Geolocalizacion.getFeatures()[0].getGeometry().getCoordinates()[1];
            } else {
                usr_lon = 0;
                usr_lat = 0;
            }

            var url = `urbano2_cmd.php?cmd=buscarlineas`;
            var params = {
                lat1: ol.proj.toLonLat([lon1,lat1])[1],
                lon1: ol.proj.toLonLat([lon1,lat1])[0],
                lat2: ol.proj.toLonLat([lon2,lat2])[1],
                lon2: ol.proj.toLonLat([lon2,lat2])[0],
                usr_lat: ol.proj.toLonLat([usr_lon,usr_lat])[1],
                usr_lon: ol.proj.toLonLat([usr_lon,usr_lat])[0],
                resultados: resultados,
                continua: continua,
                email: email,
                userkey: userkey,
                            };
            if (j){params.j = j;}
            if (conf){params.conf = conf;}

            nfetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(params),
                timeout: fetchTimeout,
            }).then(a=>{
                if (a){
                    if(a.ok){
                        return a.json();
                    }
                    if (a.status == 408){
                        location.reload();
                    }
                    document.getElementById('pantalla_gris').style.display = 'none';
                    document.getElementById('pantalla_gris_msg').innerHTML = '';
                }
                
            }).then(data=>{
                document.getElementById('pantalla_gris').style.display = 'none';
                document.getElementById('pantalla_gris_msg').innerHTML = '';
                if(document.getElementById('buscandoMsg')){
                    document.getElementById('buscandoMsg').remove();
                }
                
                lineaSeleccionada = null;
                
				if(document.getElementById('compartir')){
					document.getElementById('compartir').style.display = 'inline-block';
				}

                if (!data){
                    data = {lineas:[], combinaciones:[], continua:0};
                }

                data.lineas.forEach(linea => {
                    existe = false;
                    const lineas = document.getElementById('lineas');
                    for (let i = 0; i < document.getElementById('lineas').childNodes.length; i++) {
                        if (document.getElementById('lineas').childNodes[i].getAttribute('linea') == `${linea.id}-${linea.cliente_id}`){
                            existe = true;
                        }
                    }
                    if (!existe){
                        marquesina = '';
                        if (linea.nombre.length > 8){
                            marquesina = 'marquesina ';
                        }

                                                lineas.innerHTML +=
                            `<div id="linea${linea.id}" 
                            linea="${linea.id}-${linea.cliente_id}"
                            onclick="combi=null;seleccionalinea(${linea.id},${linea.cliente_id},'${linea.duracion}','${linea.distancia}','${linea.sentido}','${linea.origen}-${linea.destino}','','${linea.ruta}'); rutas_selected = [${linea.rutas.toString()}]"
                            style="display: flex; flex-direction: column; align-items: center; position: relative; background-color:
                            white; min-width: 110px; height: 90px; margin-right: 5px;    overflow: hidden; border-radius: 15px;">
                                <div style="display: flex; flex-direction: row; justify-content: space-evenly; width: 100%; font-size: 28px;">
                                    <div>
                                        <div style="position: absolute;
                                        top: 9px;
                                        left: 29px;
                                        width: 22px;
                                        height: 21px;
                                        background-color: white;"></div>
                                        <i class="fa-solid fa-bus-simple" style="position: relative; color:${linea.color}; margin-right: 30px;"></i>
                                    </div>
                                    <div style="background-color: #0099cb; color: white; width: 65px; height: 65px; position: absolute;
                                        top: -26px; right: -20px; border-radius: 50px; padding: 28px 18px 0px 7px;font-size: 19px;
                                        font-weight: bold; text-align: center;">
                                        ${linea.nombre}
                                    </div>
                                </div>
                                <div class="w3-center w3-medium" style="position:relative; margin:-5px; overflow: hidden" id="demora_${linea.id}">
                                    <i class="fa fa-spinner w3-spin" style="font-size:24px; margin-top:10px"></i>
                                </div>
                                <!--<div style="position: absolute;top: 105px;left: 0px;white-space: nowrap;width: 100%;overflow: hidden;">
                                    <div style="width:100%; text-align:center">
                                        Viaje ${linea.duracion}
                                    <div>
                                </div> -->
                            </div>`;
                        
                        calcula_demora_de_linea(linea.id, linea.cliente_id,'',linea.ruta,linea.origen,true,linea.rutas);
                        document.getElementById('demora_' + linea.id).setAttribute('color', linea.color);
                    }
                });

                data.combinaciones.forEach(combinacion => {
                    existe = false;
                    for (let i = 0; i < document.getElementById('lineas').childNodes.length; i++) {
                        if (document.getElementById('lineas').childNodes[i].getAttribute('linea') == `${combinacion.linea_origen.id}-${combinacion.linea_origen.cliente_id} ${combinacion.linea_destino.id}-${combinacion.linea_destino.cliente_id}`){
                            existe = true;
                        }
                    }
                    if(!existe){
                        display = 'block';

                        if ((combinacion.linea_origen.nombre.length + combinacion.linea_destino.nombre.length) > 8){
                            marquesina = 'marquesina ';
                        } else {
                            marquesina = '';
                        }

                        document.getElementById("lineas").innerHTML +=
                            `<div linea="${combinacion.linea_origen.id}-${combinacion.linea_origen.cliente_id} ${combinacion.linea_destino.id}-${combinacion.linea_destino.cliente_id}" id="linea${combinacion.linea_origen.id}-${combinacion.linea_destino.id}"
                            onclick="combi={};combi.lat=${combinacion.trasb_coord.lat};combi.lon=${combinacion.trasb_coord.lon};seleccionalinea('${combinacion.linea_origen.id}-${combinacion.linea_destino.id}','${combinacion.linea_origen.cliente_id}-${combinacion.linea_destino.cliente_id}','${combinacion.duracion}','${combinacion.distancia}','${combinacion.linea_origen.sentido}-${combinacion.linea_destino.sentido}','${combinacion.parada_origen}-${combinacion.parada_destino}','${combinacion.parada_origenA}-${combinacion.parada_destinoB}','${combinacion.linea_origen.ruta}-${combinacion.linea_destino.ruta}'); rutas_selected = [${combinacion.linea_origen.rutas}]" class="w3-container w3-card-4 w3-border w3-round-large"
                            style="font-size:14px !important; position: relative; background-color: white; width:7vw; min-width:110px; max-width:150px;height:135px;
                            display:${display};padding: 5px 3px 5px 3px;">
                                <div class="w3-center" style="position:relative; top:7px;">
                                    <svg version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; fill-rule:evenodd; clip-rule:evenodd; height:35px"
                                    viewBox="0 0 91.42 43.49" >o
                                    <polygon style="fill:none" points="0,0 91.42,0 91.42,43.49 0,43.49 "/>
                                    <polygon style="fill:#FEFEFE" points="55.53,6.08 85.47,6.08 85.47,34.51 55.53,34.51 "/>
                                    <path style="fill:${combinacion.linea_destino.color}" d="M62.2 36.65l0 3.36c0.05,0.73 -0.74,1.63 -1.74,1.63l-3.33 0c-0.62,0 -1.28,-0.98 -1.37,-1.54l-0.09 -3.45c-0.65,0 -2.57,-1 -2.57,-2.5l0 -12.31c-1.2,-0.04 -2.67,-0.77 -2.67,-1.99l0 -6.08c0,-1.01 1.2,-2.06 2.67,-2.16l0 -3.83c0,-1.05 0.31,-1.81 1.33,-2.41 3.83,-2.22 8.4,-3.72 15.97,-3.82 7.56,0.1 12.13,1.6 15.96,3.82 1.02,0.6 1.34,1.36 1.34,2.41l0 3.83c1.47,0.1 2.66,1.15 2.66,2.16l0 6.08c0,1.22 -1.46,1.95 -2.66,1.99l0 12.31c0,1.5 -1.93,2.5 -2.58,2.5l-0.09 3.45c-0.09,0.56 -0.75,1.54 -1.37,1.54l-3.33 0c-0.99,0 -1.78,-0.9 -1.74,-1.63l0 -3.36 -16.39 0zm1.21 -29.57l13.97 0c0.28,0 0.5,0.22 0.5,0.49l0 1.17c0,0.27 -0.22,0.5 -0.5,0.5l-13.97 0c-0.28,0 -0.5,-0.23 -0.5,-0.5l0 -1.17c0,-0.27 0.22,-0.49 0.5,-0.49zm6.13 17.42l-10.66 0c-0.69,0 -1.25,-0.56 -1.25,-1.25l0 -10.23c0,-0.68 0.56,-1.24 1.25,-1.24l10.66 0 0 12.72zm12.38 0l-10.67 0 0 -12.72 10.67 0c0.68,0 1.24,0.56 1.24,1.24l0 10.23c0,0.69 -0.56,1.25 -1.24,1.25zm-22.68 3.45c1.49,0 2.69,1.2 2.69,2.68 0,1.48 -1.2,2.68 -2.69,2.68 -1.48,0 -2.68,-1.2 -2.68,-2.68 0,-1.48 1.2,-2.68 2.68,-2.68zm22.72 0c-1.48,0 -2.68,1.2 -2.68,2.68 0,1.48 1.2,2.68 2.68,2.68 1.48,0 2.68,-1.2 2.68,-2.68 0,-1.48 -1.2,-2.68 -2.68,-2.68z"/>
                                    <polygon style="fill:#282829" points="50.11,23.83 45.33,16.81 45.33,21.75 41.8,21.75 41.8,23.83 41.8,25.92 45.33,25.92 45.33,30.86 "/>
                                    <polygon style="fill:#FEFEFE" points="6.49,6.08 36.43,6.08 36.43,34.51 6.49,34.51 "/>
                                    <path style="fill:${combinacion.linea_origen.color}" d="M13.16 36.65l0 3.36c0.05,0.73 -0.74,1.63 -1.74,1.63l-3.33 0c-0.62,0 -1.28,-0.98 -1.37,-1.54l-0.09 -3.45c-0.65,0 -2.57,-1 -2.57,-2.5l0 -12.31c-1.2,-0.04 -2.67,-0.77 -2.67,-1.99l0 -6.08c0,-1.01 1.2,-2.06 2.67,-2.16l0 -3.83c0,-1.05 0.31,-1.81 1.33,-2.41 3.83,-2.22 8.4,-3.72 15.97,-3.82 7.56,0.1 12.13,1.6 15.96,3.82 1.02,0.6 1.34,1.36 1.34,2.41l0 3.83c1.47,0.1 2.66,1.15 2.66,2.16l0 6.08c0,1.22 -1.47,1.95 -2.66,1.99l0 12.31c0,1.5 -1.93,2.5 -2.58,2.5l-0.09 3.45c-0.09,0.56 -0.75,1.54 -1.37,1.54l-3.33 0c-0.99,0 -1.78,-0.9 -1.74,-1.63l0 -3.36 -16.39 0zm1.21 -29.57l13.97 0c0.28,0 0.5,0.22 0.5,0.49l0 1.17c0,0.27 -0.22,0.5 -0.5,0.5l-13.97 0c-0.28,0 -0.5,-0.23 -0.5,-0.5l0 -1.17c0,-0.27 0.22,-0.49 0.5,-0.49zm6.13 17.42l-10.66 0c-0.69,0 -1.25,-0.56 -1.25,-1.25l0 -10.23c0,-0.68 0.56,-1.24 1.25,-1.24l10.66 0 0 12.72zm12.38 0l-10.67 0 0 -12.72 10.67 0c0.68,0 1.24,0.56 1.24,1.24l0 10.23c0,0.69 -0.56,1.25 -1.24,1.25zm-22.68 3.45c1.49,0 2.69,1.2 2.69,2.68 0,1.48 -1.2,2.68 -2.69,2.68 -1.48,0 -2.68,-1.2 -2.68,-2.68 0,-1.48 1.2,-2.68 2.68,-2.68zm22.72 0c-1.48,0 -2.68,1.2 -2.68,2.68 0,1.48 1.2,2.68 2.68,2.68 1.48,0 2.68,-1.2 2.68,-2.68 0,-1.48 -1.2,-2.68 -2.68,-2.68z"/>
                                    </svg>
                                </div>
                                <div style="overflow:hidden;">
                                    <div class="${marquesina}w3-center w3-large " style="position:relative; margin-top: 5px;white-space: nowrap;overflow: hidden">
                                        ${combinacion.linea_origen.nombre} - ${combinacion.linea_destino.nombre}
                                    </div>
                                </div>
                                <div class="w3-center w3-medium" style="font-size:12px !important; position:relative; overflow: hidden" id="demora_${combinacion.linea_origen.id}-${combinacion.linea_destino.id}">
                                    <i class="fa fa-spinner w3-spin" style="font-size:24px; margin-top:10px"></i>
                                </div>
                                <!-- <div style="position: absolute;top: 105px;left: 0px;white-space: nowrap;width: 100%;overflow: hidden;">
                                    <div style="width:100%; text-align:center">
                                        Viaje ${combinacion.duracion}
                                    <div>
                                </div> -->
                            </div>`;
                        combi={};
                        combi.lat=combinacion.trasb_coord.lat;
                        combi.lon=combinacion.trasb_coord.lon;

                        calcula_demora_de_linea(combinacion.linea_origen.id+'-'+combinacion.linea_destino.id,
                            combinacion.linea_origen.cliente_id+'-'+combinacion.linea_destino.cliente_id,
                            combinacion.trasb_coord,
                            combinacion.linea_origen.ruta+'-'+combinacion.linea_destino.ruta,
                            combinacion.parada_origen.split('-')[0]+'-'+combinacion.parada_origenA.split('-')[0],
                            true,
                            combinacion.linea_origen.rutas
                        );// , combinacion.linea_destino.id);
                        
                        document.getElementById('demora_' + combinacion.linea_origen.id + '-' + combinacion.linea_destino.id).setAttribute('color', combinacion.linea_origen.color);
                    }
                });
                
                if(data.combinaciones.length > 0){
                    strLinea2 = '-'+data.combinaciones[0].linea_destino.id;
                } else {
                    '';
                }
                
                if(data.continua > 0){
                    const resultados = [];
                    for(let i = 0; i < document.getElementById('lineas').childElementCount; i++){
                        a = document.getElementById('lineas').children[i].getAttribute('linea');
                        
                        b = a.split(' ');
                        if(b[1]){
                            resultados.push({
                                linea1: b[0].split('-')[0],
                                cliente1: b[0].split('-')[1],
                                linea2: b[1].split('-')[0],
                                cliente2: b[1].split('-')[1]
                            });
                        } else {
                            resultados.push({
                                linea1: b[0].split('-')[0],
                                cliente1: b[0].split('-')[1]
                            });
                        }
                        
                        
                    }
                    iniciarBuscarLineas(lon1,lat1,lon2,lat2,entorno,resultados, data.continua);
                }
                ocultaCombinacionesNoTroncales();

            }).catch(error=>{
                document.getElementById('pantalla_gris').style.display = 'none';
                document.getElementById('pantalla_gris_msg').innerHTML = '';
                if(document.getElementById('buscandoMsg')){
                    document.getElementById('buscandoMsg').remove();
                }
                if (error instanceof DOMException && error.name === 'AbortError') {
                    console.log('timeout');
                } else {
                    console.log('error', error);
                }
                
            });
        }


        map.on('dblclick', (evt)=>{
            evt.preventDefault();
            
                    });

        map.on('click', function(evt) {
            const feature = map.forEachFeatureAtPixel(evt.pixel, feature=>feature);
            
            if (feature && feature.values_.tipo == 'parada') {

                const coordinate = feature.getGeometry().getCoordinates();
                parada_seleccionada = feature.values_.codigo;
                if(modoVista == 'verLineas'){
                    clearTimeout(ini_coches_ruta);
                    
                    consutacoches_por_ruta(rutaSeleccionada, feature.values_.cliente);
                }
                if(modoVista == 'seguimientoCoche'){
                    clearTimeout(ini_coches_ruta);
                    consutacoches_por_ruta(rutaSeleccionada, feature.values_.cliente, coche_seleccionado);
                }
                
                popup_parada(feature.values_.nombre, feature.values_.codigo, feature.values_.linea, coordinate, 'no', feature.values_.cliente, 'parada',feature.values_.sentido, feature.values_.linea_nombre);


                ParadasSelec.clear();
                nueva_parada = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat(coordinate)),
                    tipo: 'parada',
                    linea:feature.values_.linea,
                    nombre:feature.values_.nombre,
                    sentido:feature.values_.sentido,
                    codigo:feature.values_.codigo,
                });
                
                ParadasSelec.addFeature(nueva_parada);


            } else if (feature && feature.values_.tipo == 'coche') {
                                popup_parada(feature.values_.coche, '', 0, feature.getGeometry().getCoordinates(), 'no', feature.values_.cliente, 'coche',
                null,null,feature.values_.rampa);
                            } else {
                var elementpopup = document.getElementById('popup');
                elementpopup.innerHTML = ``;
                ParadasSelec.clear();

                if (feature){
                }
                parada_seleccionada = '';
            }

        });

        parada_seleccionada = null;

        const proxArrib = new proximosArribos('proxarrib_solapa','cbaciudad', '');
                const onlygps_array = {"408":false,"411":false,"421":false,"422":false};

        proxArrib.setConfiguration({
            reloadSec: 60,
            finalSpace: 550,
            onlygps:false,
            onlygps_array:onlygps_array,
            ver_en_mapa: true,
            control:false,

        });
        

        const dialogo = new nDialogo({
            title: null, 
            body: null,
            background : true,
            backgroundCloseable : true,
            btnClose : false,
            width:'300px',
            height:null
        });
        
        


        const proximos_arribos = (codigo)=>{
            document.getElementById('proxarrib').style.zIndex = 1;
            document.getElementById('proxarrib').style.display = 'block';
            document.getElementById('proxarrib_solapa').innerHTML = `
            <div class="w3-display-middle"><i class="fa fa-spinner w3-spin" style="font-size:64px"></i></div>
            `;

            proxArrib.setParada(codigo);
            proxArrib.start();
        }

        const compartirCoche = (coche,cliente)=>{
                        var win = window.open(`whatsapp://send?text=https://micronauta4.dnsalias.net/web/urbano/?conf=cbaciudad%26coche=${coche}%26cliente=${cliente}%26ruta=${rutaSeleccionada}`, '_blank');
        }

        const popup_parada = (nombre, codigo, linea, coordenadas, centrar = 'no', cliente, mostrarBoton, sentido, linea_nombre, rampa)=>{
            var elementpopup = document.getElementById('popup');
            if(modoVista == 'verParadas'){
                var ver_horarios_btn = ``;        
            } else {
                var ver_horarios_btn = '';
            }
            if (mostrarBoton == 'no') {
                elementpopup.innerHTML = `<div style="background-color:white; border:solid black 1px">${nombre}</div>`;
            } else if(mostrarBoton == 'coche'){
                elementpopup.innerHTML = `<div class="w3-center w3-animate-opacity" style="text-align: center;; margin-top:-60px">
                    <div class="" style="padding:0px;height:33px;" onclick="compartirCoche(${nombre},${cliente})">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" height="33px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                            viewBox="0 0 2160.78 2094.32">
                            <path style="fill:#FFFFFF" d="M2022.94 1380.46l43.02 -101.89c54.82,-234.91 24.45,-460.36 -72.39,-676.14 -49.84,-111.05 -159.77,-258.24 -247.34,-333.19 -506.07,-433.15 -1174.98,-310.58 -1523.03,132.09 -320.06,407.08 -288.5,981.69 47.05,1349.65 297.28,326 763.47,437.28 1189.67,257.99 83.03,-34.93 109.01,-41.29 152.72,-76.96 240.02,154.51 430.53,75.02 535.12,-157.31 45.74,-162.66 -37.14,-278.52 -124.84,-394.25z"/>
                            <path style="fill:#085A9D" d="M1712.65 1494.2c12.58,-41.69 -31.48,-56.46 -26.95,-90.57 1.72,-12.97 0.16,-6.08 5.95,-14.53 29.06,-18.54 36.69,12.56 62.09,17.44 25.01,4.81 42.72,-12.28 44.77,-31.71 3.88,-36.62 -96.62,-147.59 -133.24,-202.45 -11.02,-16.51 -25.31,-33.22 -33.9,-48.97 -13.13,-24.07 -11.62,-55.86 25.9,-44.45 19.92,6.06 59.99,69.45 77.75,94.55l215.27 287.67c40.05,51.54 108.99,143.3 96.09,241.42 -18.38,139.78 -217.05,298.13 -399.12,111.02 -37.04,-38.07 -133.17,-153.92 -122.82,-206.45 37.13,-9.74 104.44,51.39 110.89,-13.66 3.92,-39.53 -71.71,-70.47 -14.78,-102.15 14.26,-2.86 75.09,59.14 92.09,2.83zm-48.56 -184.27c-22.98,19.68 -40.51,28.69 -52.12,63.54 -16.6,49.84 -10.95,32.46 -48.23,60.2 -35.67,26.54 -24.18,37.89 -41.46,74.06 -11.33,23.7 -38.27,27.61 -57.78,50.8 -71.86,85.43 22.99,166.68 108.03,285.75 150.49,210.74 470.26,165.16 541.29,-84.53 46.51,-163.48 -62.45,-289.75 -145.03,-398.73 -17.17,-22.66 -30.37,-40.94 -47.45,-61.99 -36.07,-44.44 -63.26,-80.15 -94.3,-124.35 -52.77,-75.14 -130.11,-245.65 -246.13,-150.53 -126.59,103.79 70.59,222.06 83.18,285.78zm-536.84 -585.09c-1.2,-65.22 -15.02,-80.18 15.55,-138.4 65.67,-125.05 245.2,-120.89 308.07,-3.54 70.93,132.39 -35.56,280.53 -179.46,260.55 -50.45,-7 -68.63,-29.91 -104.72,-53.1 -37.41,13.15 -294.96,163.32 -323.63,186.02 0.16,24.75 7.05,44.69 5.69,70.81 -1.21,23.16 -9,38.02 -9.59,60.4 50.15,27.21 106.37,60.37 159.14,91.52 38.21,22.56 129.29,80.9 162.92,90.44 33.8,-20.21 53.05,-50.92 109.2,-57.61 160.57,-19.12 246.98,142.17 180.39,258.41 -65.12,113.68 -231.55,127.6 -306.11,0.64 -34.81,-59.26 -14.1,-78.58 -21.45,-137.31 -22.5,-14.88 -318.27,-187.89 -328.39,-186.19 -61.34,38.19 -112.68,67.29 -200.88,26.28 -48.9,-22.74 -100.26,-86.52 -97.06,-162.68 5.35,-126.87 153.17,-238.39 303.4,-117.1 17.03,-4.67 312.86,-175.32 326.92,-189.13zm520.75 579.62c-18.13,-52.51 -121.41,-114.07 -120.27,-202.16 1,-77.67 103.86,-151.5 178.66,-102.36 49.74,32.68 241.24,342.25 279.62,352.69l12.91 -41.9c3.74,-14.5 6.53,-29.17 10.37,-45.66 51.63,-221.21 23.03,-433.52 -68.17,-636.71 -46.93,-104.58 -150.46,-243.19 -232.92,-313.76 -476.56,-407.89 -1106.48,-292.47 -1434.22,124.39 -301.4,383.35 -271.67,924.45 44.31,1270.95 279.95,306.99 718.95,411.78 1120.3,242.95 78.19,-32.89 97.62,-52.74 139.48,-72.2 -5.68,-22.81 -80.45,-120.77 -99.61,-144.36 -122.18,-150.44 2.22,-200.36 23.57,-222.54 19.74,-20.5 16.34,-54.94 41.23,-78.68 34.85,-33.22 37.91,-9.43 56.53,-64.77 12.41,-36.89 27,-39.3 48.2,-65.89z"/>
                        </svg>
                    </div>
                    <div style="text-shadow: 0px 0px 4px rgba(255,255,255,1);font-weight: bold;">${rampa==1?'rampa':(rampa==2?'elevador':'')}</div>
                                    </div>`;
                            } else if(mostrarBoton == 'parada'){
                                
                
                const newdiv = document.createElement('div');
                newdiv.style.position = 'absolute';
                newdiv.style.opacity = 0;
                newdiv.innerHTML = nombre;
                document.body.appendChild(newdiv);
                const ancho = newdiv.offsetWidth;
                let clase = '';
                if (ancho > 170) {
                    clase = 'marquee__content'
                }
                newdiv.remove();


                elementpopup.innerHTML = `<div style="background-color: white;padding: .5em 1em .2em;border-radius: 1em;max-width: 190px; overflow: hidden;">
                    <div class="${clase}" style="white-space: nowrap;">
                        ${nombre}
                    </div>
                    <div style="text-align: center">
                        <label id="popup_parada_nombre_demora"></label>
                    </div>
                    <div style="text-align: center">
                        <button style="    border: unset;border-radius: .5em;padding: .5em 0.8em;color: white;background-color: #6096e2;"
                        onclick="proximos_arribos('${codigo}',${linea},'${nombre}',${cliente},'${sentido}')">Prox. Llegadas</button>
                    </div>
                    
                </div>
                <div style="color: white;margin: 0 auto; width:0px; height: 0; border-left: 10px solid transparent; border-top: 10px solid ; border-right: 10px solid transparent;">
                </div>
                `;

                
                                                const cli_show_horario = {"408":true,"411":true,"421":true,"422":true};
                if (linea && cli_show_horario[cliente]) {
                    document.getElementById('btn_ver_horarios').style.display = 'block';
                    
                    document.getElementById('btn_ver_horarios').setAttribute('onclick',`ver_horarios('${codigo}',${linea},'${nombre}',${cliente},'${sentido}')`);
                    document.getElementById('btn_ver_horarios').innerHTML = `Ver Horarios diagramados para la linea ${linea_nombre}`;
                } else {
                    document.getElementById('btn_ver_horarios').style.display = 'none';
                }
                            } else {
                elementpopup.innerHTML = `
                <div class="w3-center w3-animate-opacity" style="text-align: center">
                    <div class="w3-tag w3-round w3-theme-d1" style="padding:3px; width:170px">
                        <div class="w3-tag w3-round w3-theme-d1 w3-border w3-border-white">
                            <div id="popup_parada_nombre">
                                ${nombre}<label id="popup_parada_nombre_demora"></label>
                            </div>
                                ${ver_horarios_btn}
                            <div>
                                <button class="w3-btn w3-theme-d4 w3-round-large" onclick="proximos_arribos('${codigo}',${linea},'${nombre}',${cliente},'${sentido}')">Prox. Llegadas</button>
                            </div>
                        </div>
                    </div>
                    <div class="w3-theme-d1-invert" style="margin: 0 auto; width:0px; height: 0; border-left: 10px solid transparent; border-top: 10px solid ; border-right: 10px solid transparent;">
                    </div>
                </div>`;
            }
            
            
            var popup = new ol.Overlay({
                positioning: 'bottom-center',
                offset: [0, -13],
                element: elementpopup
            });
            popup.setPosition(coordenadas);
            map.addOverlay(popup);

            if (centrar == 'si') {
                vista.animate({
                    center: coordenadas
                });
            }
        }

        function carga_direccion_origen_de_coordenada(lon, lat) {
            textoDeEspera(document.getElementById('input_origen'));
            nfetch(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=0&zoom=18&lat=${lat}&lon=${lon}`,{timeout: fetchTimeout,})
            .then(response => response.json()).then(data => {
                clearTimeout(timeoutHTMLInput);
                document.getElementById('input_origen').value = descripcion_calle(data.display_name);
            })
            .catch(error => {
                clearTimeout(timeoutHTMLInput);
                document.getElementById('input_origen').value = 'No se pudo obtener la direcci&oacute;n';
            });
        }


        function carga_direccion_destino_de_coordenada(lon, lat) {
            textoDeEspera(document.getElementById('input_destino'));
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=0&zoom=18&lat=${lat}&lon=${lon}`,{timeout: fetchTimeout,})
            .then(response => response.json()).then(data => {
                clearTimeout(timeoutHTMLInput);
                document.getElementById('input_destino').value = descripcion_calle(data.display_name);
            })
            .catch(error => {
                clearTimeout(timeoutHTMLInput);
                document.getElementById('input_destino').value = 'No se pudo obtener la direcci&oacute;n';
            });
            ;
        }

        let timeoutHTMLInput = null;
        const textoDeEspera = (HTMLInput)=>{
            if (HTMLInput.value === '...') {
                HTMLInput.value = '.';
            }else if (HTMLInput.value === '..') {
                HTMLInput.value = '...';
            }else if (HTMLInput.value === '.') {
                HTMLInput.value = '..';
            }else{
                HTMLInput.value = '.';               
            }
            clearTimeout(timeoutHTMLInput);
            timeoutHTMLInput = setTimeout(()=>{
                textoDeEspera(HTMLInput);
            }, 500);
        }


        const calcula_demora_de_linea = async (linea, cliente, coordenada_destino = false, ruta = 0, parada=0, ordena=true, rutas=[])=>{

            
            if (ruta == 0) {
                ruta = rutaSeleccionada;    
            }

            if (parada == 0) {
                parada = paradaOrigenSeleccionada;
            }

            if (rutas.length > 0){
                rutas = rutas.join(",");
            }

            if (rutas_selected.length > 0){
                rutas = rutas_selected;
            }



            

            var lon1 = origen.getGeometry().getCoordinates()[0];
            var lat1 = origen.getGeometry().getCoordinates()[1];
            var lon2 = destino.getGeometry().getCoordinates()[0];
            var lat2 = destino.getGeometry().getCoordinates()[1];

            if (coordenada_destino){
                lat2 = coordenada_destino.lat;
                lon2 = coordenada_destino.lon;
            }

            if(linea){
                linea1 = linea.toString().split('-')[0];
                linea2 = linea.toString().split('-')[1];
            }
            
            cliente1 = cliente.toString().split('-')[0];
            cliente2 = cliente.toString().split('-')[2];

            [lon1_, lat1_] = ol.proj.toLonLat([lon1, lat1]);
            [lon2_, lat2_] = ol.proj.toLonLat([lon2, lat2]);
            var url = `urbano2_cmd.php?cmd=calcula_demora_de_linea&lat1=${lat1_}&lon1=${lon1_}&lat2=${lat2_}&lon2=${lon2_}&linea=${linea1}-${linea2}&ruta=${ruta}&rutas=${rutas}&parada=${parada}&cliente=${cliente1}${conf?'&conf='+conf:''}`;

            const a = await nfetch(url,{timeout: fetchTimeout,});
            let data = await a.json();

                coches = [];
                proximo = [];

                if (document.getElementById('demora_' + linea)) {
                    document.getElementById('demora_' + linea).innerHTML = '';
                }

                data = data.proximos;
                
                if (data.length == 0) {
                    data.push({
                        proximo: '',
                        coche: 0,
                        segundos: 999999
                    });
                }
                
                demora = data[0];
                if (demora.proximo.toUpperCase() == 'LLEGANDO'){
                    texto_proximo = 'Proximo';
                } else {
                    texto_proximo = 'Proximo en';
                }

                if (demora.proximo == ''){
                    texto_proximo = '';
                }

                                if (demora.coche > 0) {
                    coches.push(demora.coche);
                    proximo.push(demora.proximo);
                    let color = 'black';
                    if (demora.segundos < 150 ){ // 3minutos
                        color = '#008000'; //verde
                    } else if (demora.segundos < 870 ){ // 15minutos
                        color = '#ffa500'; // naranja                         ;
                    }
                    if (document.getElementById('linea'+linea)){
                        document.getElementById('linea'+linea).setAttribute('c_lat',demora.c_lat);
                        document.getElementById('linea'+linea).setAttribute('c_lon',demora.c_lon);
                        document.getElementById('linea'+linea).setAttribute('p_lat',demora.p_lat);
                        document.getElementById('linea'+linea).setAttribute('p_lon',demora.p_lon);
                    }
                    document.getElementById('demora_' + linea).innerHTML += `<div style="color:${color}; font-size: 16px; font-weight: bold;">${demora.proximo}</div>`;
                    document.getElementById('demora_' + linea).innerHTML += `<div style="color:black; font-size: 11px; font-weight: normal;${demora.segundos == 999999?'display:none;':''}">
                        <i class="fas fa-location-arrow" style="color:#008000;font-size: 12px;"></i>
                        Seg&uacute;n GPS
                    </div>`;
                }else{
                    let color = 'black';
                    if (demora.segundos < 150 ){ // 3minutos
                        color = '#008000'; //verde
                    } else if (demora.segundos < 870 ){ // 15minutos
                        color = '#ffa500'; // naranja                         ;
                    }
                    document.getElementById('demora_' + linea).innerHTML += `<div style="color:${color}; font-size: 16px; font-weight: bold;">${demora.proximo}</div>`;
                    document.getElementById('demora_' + linea).innerHTML += `<div style="color:black; font-size: 11px; font-weight: normal;${demora.segundos == 999999?'display:none;':''}">
                        <i class="fas fa-calendar-alt" style="color:#e69393;font-size: 12px;"></i>
                        Seg&uacute;n Diagrama
                    </div>`;
                }
                                data.forEach(a=>{
                    if (a.coche > 0){
                        coches.push(a.coche);
                    }
                });
                
                

                if (document.getElementById('demora_' + linea)){
                    document.getElementById('demora_' + linea).setAttribute('cliente', cliente);
                    document.getElementById('demora_' + linea).setAttribute('coches', JSON.stringify(coches));
                    document.getElementById('demora_' + linea).setAttribute('proximo', JSON.stringify(proximo));
                    if (data.length > 0 ){
                        document.getElementById('linea' + linea).setAttribute('segundos',data[0].segundos);
                        document.getElementById('linea' + linea).setAttribute('coche',data[0].coche);

                        
                        if (ordena){
                            
                            if (document.getElementById('lineas').childNodes.length > 1){                                 cambia = true;
                                while (cambia){
                                    cambia = false;
                                    for (i=1; i < document.getElementById('lineas').childNodes.length; i++){
                                        
                                        line_card_prev = document.getElementById('lineas').childNodes[i-1];
                                        line_card = document.getElementById('lineas').childNodes[i];
                                        
                                        if ( (line_card.getAttribute('coche')> 0 && line_card_prev.getAttribute('coche') == 0) ||  // prioriza coche sobre teorico
                                        (line_card.getAttribute('coche') > 0 && line_card_prev.getAttribute('coche') > 0 && parseInt(line_card.getAttribute('segundos')) < parseInt(line_card_prev.getAttribute('segundos'))) || // prioriza coche con menor tiempo
                                        (line_card.getAttribute('coche') == 0 && line_card_prev.getAttribute('coche') == 0 && parseInt(line_card.getAttribute('segundos')) < parseInt(line_card_prev.getAttribute('segundos'))) // prioriza teorico con menor tiempo
                                        ){
                                            cambia = true;
                                            document.getElementById('lineas').insertBefore(line_card,line_card_prev);
                                        }
                                    };
                                }
                            }
                        }
                    }
                }
               
            return data;

        }

        function queryStringToJSON(queryString) {
            if (queryString.indexOf('?') > -1) {
                queryString = queryString.split('?')[1];
            }
            var pairs = queryString.split('&');
            var result = {};
            pairs.forEach(function(pair) {
                pair = pair.split('=');
                result[pair[0]] = decodeURIComponent(pair[1] || '');
            });
            return result;
        }

        var linea_selec = 0;
        var cliente_selec = 0;
        var linea2 = 0;

        

        function unselect_grupolinea(){
			const radios = document.querySelectorAll('[id^="radio_"]');
			for (let i = 0; i < radios.length; i++) {
				radios[i].checked = false;
			}
            
        }

        const inicio_con_seguimiento_coche = async (coche, cliente, ruta, linea_nombre, linea, parada = false) => {
           
       

                document.getElementById('inputVerLineas').click();
                setTimeout(() => {
                    mostrar_ver_lineas();
                    if(linea_nombre){
                        const radio = document.getElementById('radio_' + linea_nombre);
                        radio.click();
                        radio.nextElementSibling.nextElementSibling.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }, 1000);
                
                
                rutaSeleccionada=ruta;
                linea_selec=linea;
                cliente_selec=cliente;
                seleccionaTraza(cliente,ruta,document.getElementById('ruta_'+ruta+'_'+cliente),true,true);
                clearInterval(ini_coches_ruta);
                await consutacoches_por_ruta(ruta, cliente, coche);

                for(feature of Paradas.getFeatures()){
                    if(feature.values_.codigo == parada){
                        setTimeout(() => {
                            const coordinate = feature.getGeometry().getCoordinates();
                            popup_parada(feature.values_.nombre, feature.values_.codigo, feature.values_.linea, coordinate, 'no', feature.values_.cliente, 'parada',feature.values_.sentido, feature.values_.linea_nombre);            
                        }, 500);
                        break;
                    }
                }
                
                const coordenadas = [];
                if (parada) {
                    coordenadas.push(feature.getGeometry().getCoordinates());
                }
                if (Coches.getFeatures().length > 0) {
                    coordenadas.push(Coches.getFeatures()[0].getGeometry().getCoordinates());
                }
                if (coordenadas.length > 0) {
                    const max_y_min = maximoYMinimo(coordenadas);
                    vista.fit(new ol.geom.LineString([max_y_min.min, max_y_min.max]), {
                        padding: [200, 80, 80, 180],
                        duration: 1000,
                        maxZoom:18,
                    });
                }




            document.getElementById('proxarrib').style.display = 'none'; 
            modoVista = 'seguimientoCoche';
            
            rutaSeleccionada = ruta;
            document.getElementById('input_listado_lineas').checked = false;

        }

        const inicio_con_busqueda = (lat1,lon1,lat2,lon2,e)=>{
            document.getElementById('input_panel_principal').checked = true;
            buscar_lineas_btn.style.display = 'none';
            document.getElementById('map').style.marginTop = 0;
            const coordenadas = {
                origen: {
                    lat: lat1,
                    lon: lon1
                },
                destino: {
                    lat: lat2,
                    lon: lon2
                }
            };
           
            setTimeout(() => {
                document.getElementById('entorno').value = e;
                document.getElementById('range').value = e;
                origen_destino_inicial(coordenadas);
                posicionarOrigenDestino('origen', '', parseFloat(lon1), parseFloat(lat1));
                posicionarOrigenDestino('destino', '', parseFloat(lon2), parseFloat(lat2));
                buscarlineas();
                
            }, 1000);
            
        }

        let listadoDeLineas = [];
        let listadoDeGrupos = [];

        const actualizaMiLocalizacion = ()=>{
            milocalizacion('X');
            setTimeout(actualizaMiLocalizacion, 1000*60*3);
        }

        const ver_paradas = ()=>{
            if (Geolocalizacion.getFeatures()[0]){
                coordenada_actual = Geolocalizacion.getFeatures()[0].getGeometry().getCoordinates();
                lat = coordenada_actual[1];
                lon = coordenada_actual[0];
            } else {
                let result_origen = false;
                if (typeof(Storage) !== "undefined") {
                    or = localStorage.getItem("origen"+conf);
                    if (or) {
                        result_origen = JSON.parse(or);
                    }
                }
                if (result_origen) {
                    coordenada_actual = [result_origen.lon, result_origen.lat];
                    lat = result_origen.lat;
                    lon = result_origen.lon;
                } else {
                    var center = map.getView().getCenter();
                    lat = center[1];
                    lon = center[0];
                }
            }

            var params = {
                conf: conf,
                lat: ol.proj.toLonLat([lon, lat])[1],
                lon: ol.proj.toLonLat([lon, lat])[0],
                            };
            pantalla_gris.style.display = 'block';
            pantalla_gris_msg.innerHTML = 'buscando paradas cercanas...';
            nfetch(`urbano2_cmd.php?cmd=paradas_cercanas`,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(params),
                timeout: fetchTimeout,
            }).then(a=>{
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data => {
                if (data.ok){
                    pantalla_gris.style.display = 'none';
                    dibujaParadas(data.paradas, data.color);
                    duracion_animacion = 1000;
                    zoom = 16;
                    map.getView().animate({
                        center: [lon, lat],
                        duration: duracion_animacion
                    }, {
                        zoom: zoom,
                        duration: duracion_animacion
                    });



                }
                
            });
        }

        function brillo(color) {
            color = color.replace('#','');
            const red = parseInt(color.substring(0,2),16);
            const green = parseInt(color.substring(2,4),16);
            const blue = parseInt(color.substring(4,6),16);
            const r = red * .3;
            const g = green * .59;
            const b = blue * .11;
            const gray = r + g + b
            return gray;
        }


        const addLight = (color, amount)=>{
            let cc = parseInt(color,16) + amount;
            let c = (cc > 255) ? 255 : (cc);
            c = (c.toString(16).length > 1 ) ? c.toString(16) : `0${c.toString(16)}`;
            return c;
        }
        const lighten = (color, amount)=> {
            color = (color.indexOf("#")>=0) ? color.substring(1,color.length) : color;
            amount = parseInt((255*amount)/100);
            return color = `#${addLight(color.substring(0,2), amount)}${addLight(color.substring(2,4), amount)}${addLight(color.substring(4,6), amount)}`;
        }

        const subtractLight = (color, amount)=>{
            let cc = parseInt(color,16) - amount;
            let c = (cc < 0) ? 0 : (cc);
            c = (c.toString(16).length > 1 ) ? c.toString(16) : `0${c.toString(16)}`;
            return c;
        }
        const darken = (color, amount) =>{
            color = (color.indexOf("#")>=0) ? color.substring(1,color.length) : color;
            amount = parseInt((255*amount)/100);
            return color = `#${subtractLight(color.substring(0,2), amount)}${subtractLight(color.substring(2,4), amount)}${subtractLight(color.substring(4,6), amount)}`;
        }

        const marquee__content = (texto,tam,max_value)=>{
            const div = document.createElement('div');
            div.style.fontSize = tam;
            div.style.position = 'absolute';
            div.style.visivility = 'hidden';
            div.innerHTML = texto;
            document.body.appendChild(div);
            const ancho = div.clientWidth;
            document.body.removeChild(div);
            if (ancho > max_value){
                return 'marquee__content';
            }else{
                return '';
            }

        }
        
        const configurar_lineas_y_rutas = (data)=>{

            if (typeof(Storage) !== "undefined") {
                let lineas_favoritas = [];


                if (localStorage.getItem("lineas_favoritascbaciudad") ){
                    lineas_favoritas = JSON.parse(localStorage.getItem("lineas_favoritascbaciudad"));
                }
                
                let fav = data.lineas.filter(linea=>
                    lineas_favoritas.map(favorita=>favorita.linea_id.toString() + '-' + favorita.cliente.toString()).includes(linea.linea_id.toString() + '-' + linea.cliente.toString())
                );
                fav.forEach(favorita=>{
                    favorita['favorita'] = true;
                });
                
                const resto = data.lineas.filter(linea=>
                    !lineas_favoritas.map(favorita=>favorita.linea_id.toString() + '-' + favorita.cliente.toString()).includes(linea.linea_id.toString() + '-' + linea.cliente.toString())
                );

                data.lineas = fav.concat(resto);
            }


            b = ' ';
            a = ' ';

            listadoDeLineas = data.lineas;
            listadoDeGrupos = data.grupos;


            
            data.lineas.forEach(linea=>{
                let tam = 21;
                let pad = '10px'
                let top = 15;
                if(linea.linea_nombre.length > 4){
                    tam = 8;
                    pad = '15px 0'
                    top = 24;
                }

				                let estrellita = '';
                if (linea.favorita){
                    estrellita = `<i class="fas fa-star iconFav" id="iconRutaFav" style="
                        margin-right: 15px;
                        position: absolute;
                        top: 7px;
                        left: 9px;
                        color: white;
                        font-size: 12px;
                    "></i>`;
                }
				a += `
				<input id="radio_${linea.linea_nombre.replace(/_/g,'').trim()}" type="radio"
                    name="linea_del_listado" value="${'linea_' + linea.linea_nombre.replace(/_/g,'').trim()}"
                    lineanombre="${linea.linea_nombre.trim()}"
                    linea_id="${linea.linea_id}"
                    cliente="${linea.cliente}"
                    style="display:none">
				<input id="check_${linea.linea_nombre.replace(/_/g,'').trim()}" type="checkbox" value="${linea.linea_nombre.replace(/_/g,'').trim()}" style="display:none">
				<div color_txt="${linea.cliente==412?'#dd1d1d':'black'}" class="grupo_linea_" id="grupo_linea_${linea.linea_nombre.replace(/_/g,'').trim()}"
					style="position:relative;
					width:52px;
					height:52px;
                    min-height:52px;
                    margin: 3px 0 3px;
					color:${linea.cliente==412?'#dd1d1d':'black'};
					">
					<div>
						<div style="
							display: flex;
							width: 100%;
							height: 100%;
							justify-content: center;
							background-color: ${linea.color.trim()};
							border-radius: 40%;
							color: ${linea.cliente==412?'#dd1d1d':'white'};
							font-size: 18px;
							justify-content: center;
                            white-space: nowrap;
							"><span class="${marquee__content(linea.linea_nombre.trim(),'18px',52)}">${linea.linea_nombre.trim()}</span>
						</div>
						<label for="radio_${linea.linea_nombre.replace(/_/g,'').trim()}" class="linea_" id="linea_${linea.linea_nombre.replace(/_/g,'').trim()}"
						style="position: absolute; width:60px; height:60px; background-color:rgba(0,0,0,0);border-radius:50%">
                        ${estrellita}
						</label>
					</div>
                    
				</div>
				`;
				
                linea.rutas.forEach(ruta=>{
					                    b += `
                    <div id="ruta_${ruta.ruta_id}_${linea.cliente}" class="ruta_ ruta_linea_${linea.linea_nombre.replace(/_/g,'')}| w3-animate-top"
						style="
							color:${brillo(linea.color)>150?'black':'white'};
							background-color:${linea.color};
							width: 100%;
							max-width: 360px;
							margin: 8px 0 0;
							padding: 0px 10px;
							justify-content: center;
							align-items: center;
							color: white;
							overflow: hidden;
							height: 25px;
							display: flex;
							border-radius: 10px;
							cursor: pointer;
							white-space: nowrap;
							transition: 0.5s all ease;"
                    	onclick="rutaSeleccionada=${ruta.ruta_id};
							linea_selec=${linea.linea_id};
							cliente_selec=${linea.cliente};
							seleccionaTraza(${linea.cliente},${ruta.ruta_id},this);
							clearInterval(ini_coches_ruta);
                    		consutacoches_por_ruta(${ruta.ruta_id}, ${linea.cliente});
						">
						<span class="${marquee__content(linea.nombre,'15px',200)}" style="text-align: center; white-space: nowrap;">${ruta.ruta_nombre}</span>
					</div>
                    `;
					                });

                const input_linea_calificacion = document.getElementById('linea');
                if (input_linea_calificacion) {
                    const option = document.createElement('option');
                    option.value = linea.linea_nombre+'|'+linea.cliente;
                    option.textContent = linea.linea_nombre;
                    input_linea_calificacion.appendChild(option);
                    input_linea_calificacion.value = '';
                }

                
            });
            document.getElementById('listado_lineas').innerHTML= a;
			
			
            document.getElementById('listado_rutas').innerHTML= b;

			
			document.getElementById('listado_lineas').addEventListener('scroll',()=>{
				const b = document.getElementById('listado_lineas').scrollTop;

				document.getElementById('listado_lineas').childNodes.forEach((e)=>{

					if(e.nodeName == 'DIV'){
						const posicion_y = e.offsetTop;
						const posicion_absoluta = posicion_y - b;
						if(posicion_absoluta < -10){
							if (document.getElementById('check_' + e.id.replace("grupo_linea_", '')).checked == false){
								document.getElementById('check_' + e.id.replace("grupo_linea_", '')).checked = true;
							}
							
						} else {
							if (document.getElementById('check_' + e.id.replace("grupo_linea_", '')).checked == true){
								document.getElementById('check_' + e.id.replace("grupo_linea_", '')).checked = false;
							}
						}
					}
				});
			});

						const radios = document.querySelectorAll('[id^="radio_"]');
			for (let i = 0; i < radios.length; i++) {
				radios[i].addEventListener('change', e => {
                    if(document.getElementById('input_direcciones_favoritas')){
                        document.getElementById('input_direcciones_favoritas').checked = false;
                    }
					if (e.target.checked) {
						const nombreRutaFav = document.getElementById('nombreRutaFav');
						if(nombreRutaFav){
							nombreRutaFav.innerHTML = e.target.getAttribute('lineanombre');
						}
                        const linea_id = e.target.getAttribute('linea_id');
                        const cliente = e.target.getAttribute('cliente');
                        if(document.getElementById('input_iconRutaFav')){
                            document.getElementById('input_iconRutaFav').setAttribute('onchange',`guardarLineaFavorita({linea_id:'${linea_id}', cliente:'${cliente}'})`);
                        }
						const b = 'ruta_' + e.target.value + '|';
						ocultarClassName('ruta_');
                        mostrarClassName(b);
                        if (typeof(Storage) !== "undefined") {
                            const input_iconRutaFav = document.getElementById('input_iconRutaFav');
                            let lineas_favoritas = [];
                            if (localStorage.getItem("lineas_favoritascbaciudad") !== 'null'){
                                lineas_favoritas = JSON.parse(localStorage.getItem("lineas_favoritascbaciudad"))?
                                JSON.parse(localStorage.getItem("lineas_favoritascbaciudad")):[];
                            }
                            if (lineas_favoritas.filter(row=>row.linea_id == linea_id && row.cliente == cliente).length == 1){ // si ya esta en favoritos
                                input_iconRutaFav.checked = true;
                            } else {
                                input_iconRutaFav.checked = false;
                            }
                        }
					}
				});
			}

			ocultarClassName('ruta_');
        }

        const set_extend = (data)=>{
            contexto = data;
            vista.fit(new ol.geom.LineString([
                ol.proj.fromLonLat([contexto.max_lon, contexto.max_lat]),
                ol.proj.fromLonLat([contexto.min_lon, contexto.min_lat])
            ]), {
                padding: [80, 80, 80, 80]
            });
        }

        function iniFrame() {
            if ( window.location !== window.parent.location ){
                return true;
            } else {
                return false;
            }
        }

        function segundos_restantes(fechaHora) {
            var ahora = new Date(); 
            var fechaHoraObj = new Date(fechaHora); 
            var diferencia = fechaHoraObj.getTime() - ahora.getTime();

            if (diferencia < 0) {
                return 0;
            }

            var segundosRestantes = Math.floor(diferencia / 1000);

            return segundosRestantes;
        }

        
        function isJSON(str) {
            if (typeof str !== 'string') return false;
            try {
                const result = JSON.parse(str);
                const type = Object.prototype.toString.call(result);
                return type === '[object Object]' 
                    || type === '[object Array]';
            } catch (err) {
                return false;
            }
        }
        

        

        window.onload = function() {
   
            

            actualizaMiLocalizacion();
            
            var url = `urbano2_cmd.php?cmd=lineasyrutas`;
            var params = {
                            };
            if(a) params.a = a;
            if(j) params.j = j;
            if(conf) params.conf = conf;

                        
            // if (typeof(Storage) !== "undefined") {
            //     const lineas = localStorage.getItem("lineasyrutascbaciudad");
            //     if (lineas != null){
            //         if (isJSON(lineas)){
            //             const data = JSON.parse(lineas);
            //             configurar_lineas_y_rutas(data);
            //         }
            //     }
            // }
            
            nfetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(params),
                timeout: fetchTimeout,
            }).then(a=>{
                
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data=>{
                // if (typeof(Storage) !== "undefined") {
                //     const lineascrc = localStorage.getItem("lineasyrutascrccbaciudad");
                //     if (lineascrc != null) {
                //         if (lineascrc != data.lineascrc) {
                //             localStorage.setItem("lineasyrutascbaciudad", JSON.stringify(data));
                //             localStorage.setItem("lineasyrutascrccbaciudad", data.lineascrc);
                //             configurar_lineas_y_rutas(data);
                //         }
                //     } else {
                //         localStorage.setItem("lineasyrutascbaciudad", JSON.stringify(data));
                //         localStorage.setItem("lineasyrutascrccbaciudad", data.lineascrc);
                //         configurar_lineas_y_rutas(data);
                //     }
                // } else {
                    configurar_lineas_y_rutas(data);
                // }
                
            }).catch(error=>{
                if (error instanceof DOMException && error.name === 'AbortError') {
                    console.log('timeout');
                } else {
                    console.log('error', error);
                }
            });
            


            

            setTimeout(function() {
                document.getElementById('pantalla_inicio').style.display = 'none';
milocalizacion('A','Mi Ubicacion');                    
            }, 1000);

            var url = `urbano2_cmd.php?cmd=vista`;
            var params = {
                            };
            if(j) params.j = j;
            if(conf) params.conf = conf;

            vistaconfigurada = false;
            if (typeof(Storage) !== "undefined") {
                if (isJSON(localStorage.getItem("extend"))){
                    if (JSON.parse(localStorage.getItem("extend"))){
                        data = JSON.parse(localStorage.getItem("extend"));
                        set_extend(data);
                        vistaconfigurada = true;
                    }
                }
            }

           
            nfetch(url,{
                method: 'POST',
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(params),
                timeout: fetchTimeout,
            }).then(a=>{
                if(a.ok){
                    return a.json();
                }
                if (a.status == 408){
                    location.reload();
                }
            }).then(data=>{
                if (typeof(Storage) !== "undefined") {
                    if (isJSON(localStorage.getItem("extend"))){
                        localStorage.setItem("extend",JSON.stringify(data));
                    } else {
                        localStorage.removeItem("extend");
                    }
                }
                if (!vistaconfigurada){
                    set_extend(data);
                }
            }).catch(error=>{
                document.getElementById('pantalla_gris').style.display = 'none';
                if (error instanceof DOMException && error.name === 'AbortError') {
                    console.log('timeout');
                } else {
                    console.log('error', error);
                }
            });

            actualiza_coches = setInterval(()=>{
                if (lineaSeleccionada) {
                    coches_str = coches_array.toString();                    
                    consutacoches();
                }
            }, 15000);

            height = window.innerHeight;
            
            leyenda_segun_diagrama.style.display = 'none';
            
            
            
        };

        const toParams = (obj)=>{
            return encodeURI(
                Object.keys(obj).reduce((prev, key, i) => ( 
                    `${prev}${i!==0?'&':''}${key}=${obj[key]}`
                ), '')
            );
        }
        
    </script>
    <script>
        if(!window.frameElement){
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register("./sw.js?conf=cbaciudad")
                    .then(reg => {})
                    .catch(err => {})
            }
        }
    </script>
</body>

</html>

