<template>
  <content>
    <v-card-actions>
      <v-btn icon @click="getTab" v-if="tab == 0">
        <v-icon v-if="options.i">mdi-plus</v-icon>
        <v-dialog width="700" persistent v-model="addNave">
          <plusCard
            dialogs="1"
            @close="closePlusCard"
            :id="parque.id"
          ></plusCard>
        </v-dialog>
      </v-btn>
    </v-card-actions>
    <v-card-text>
      <v-tabs v-model="tab">
        <v-tab>
          Inquilinos
        </v-tab>
        <v-tab>
          Informacion
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-container>
            <v-row>
              <v-col sm="12" md="3" v-for="(i, k) in naves" :key="k">
                <v-card>
                  <v-card-title>
                    {{ i.name }}
                  </v-card-title>
                  <v-card-actions>
                    <v-btn icon @click="viewNave(i.id)">
                      <v-icon>mdi-eye</v-icon>
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-container>
            <v-row>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Nombre del parque en (Español)"
                  outlined
                  :disabled="!options.u"
                  v-model="parque.nombre_es"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Nombre del parque en (Ingles)"
                  outlined
                  v-model="parque.nombre_en"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Administrador del parque"
                  outlined
                  v-model="parque.adminParq"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Propietario del parque"
                  outlined
                  v-model="parque.parqProp"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6" v-if="editUb">
                <v-text-field
                  label="Ubicacion"
                  outlined
                  v-model="parque.parqIntoParq"
                  @focus="focs"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-select
                  :items="regiones"
                  label="Region"
                  outlined
                  v-model="parque.region"
                  :disabled="!options.u"
                ></v-select>
              </v-col>
              <v-col sm="12" md="6">
                <v-select
                  :items="mercados"
                  label="Mercado"
                  outlined
                  v-model="parque.mercado"
                  :value="Mercados"
                  :disabled="!options.u"
                ></v-select>
              </v-col>
              <v-col sm="12">
                <v-text-field
                  label="Tipo de industria"
                  outlined
                  :disabled="!options.u"
                  v-model="parque.tipoDeIndustria"
                ></v-text-field>
              </v-col>
              <v-col sm="12">
                <span>Superficies</span>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Superficie total"
                  outlined
                  v-model="parque.superficieTotal"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-select
                  :items="items"
                  label="Tipo de Propiedad"
                  outlined
                  v-model="parque.tipoDePropiedad"
                  :disabled="!options.u"
                ></v-select>
              </v-col>
              <v-col sm="12">
                <v-text-field
                  label="Numero de empleados"
                  outlined
                  v-model="parque.numEmpleados"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12">
                <span>Detalles</span>
              </v-col>

              <v-col sm="6">
                <span
                  >Reconocimientos: {{ parque.reconocimientoPracticas }}</span
                >
              </v-col>
              <v-col sm="6">
                <span>Infraestructura: {{ parque.ifraestructura }}</span>
              </v-col>
              <v-col sm="12" md="6">
                <v-select
                  v-model="newRecords"
                  :items="records"
                  attach
                  chips
                  outlined
                  label="Reconocimientos"
                  :disabled="!options.u"
                  multiple
                ></v-select>
              </v-col>

              <v-col sm="12" md="6">
                <v-select
                  v-model="newInfra"
                  :items="infraList"
                  attach
                  chips
                  outlined
                  label="Infraestructura"
                  :disabled="!options.u"
                  multiple
                ></v-select>
              </v-col>
              <v-col sm="12">
                <span>Contacto</span>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Nombre de contacto"
                  outlined
                  v-model="parque.contactName"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
              <v-col sm="12" md="6">
                <v-text-field
                  label="Email"
                  outlined
                  v-model="parque.contactEmail"
                  :disabled="!options.u"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-card-actions>
              <v-btn @click="updatePark" v-if="options.u"
                >Guardar Informacion</v-btn
              >
            </v-card-actions>
          </v-container>
        </v-tab-item>
      </v-tabs-items>
    </v-card-text>
  </content>
</template>
<script>
import axios from "axios";
import plusCard from "../components/plusCard";
import Swal from "sweetalert2";
export default {
  name: "parque",
  data() {
    return {
      tab: 0,
      parque: [],
      addUser: null,
      addNave: null,
      permisos: ["Eliminar", "Editar", "Agregar"],
      dataUser: {
        name: "",
        lastName: "",
        last: "",
        email: "",
      },
      users_parqs: "",
      data_to_info: "",
      data_user: false,
      naves: null,
      editUb: false,
      mercados: [
        "productos de consumo",
        "productos de uso o inversión",
        "productos industriales",
        "servicios",
        "financieros",
        "distribuidores",
        "compradores industriales",
      ],
      regiones: [
        "Región Noroeste",
        "Región Noreste",
        "Región Occidente",
        "Región Oriente",
        "Región Centronorte",
        "Región Centrosur",
        "Región Suroeste",
        "Región Sureste",
      ],
      items: ["Privado", "Público"],
      records: [
        "Norma Mexiana de Parque Industrial",
        "Parque Industrial Verde",
        "Calidad Ambiental (PROFEPA)",
        "Parque Industrial Sustentable",
        "Parque Industrial Limpio",
        "Parque Industrial Seguro",
        "OEA",
      ],
      infraList: [
        "Al menos 0.5 litros agua por segundo por ha",
        "Pavimento",
        "Banquetas",
        "Drenaje Sanitario",
        "Drenaje Pluvial",
        "Planta de tratamiento de Agua",
        "Gas Natural",
        "Alumbrado público",
        "Instalación eléctrica",
        "Subestación eléctrica",
        "Telefonía",
        "Comunicación Satelital",
        "Instalación Digital",
        "Espuela de Ferrocarril",
        "Estación de bomberos",
        "Áreas verdes o recreativas",
        "Guardería",
        "Centro de Capacitación",
        "Seguridad",
        "Transporte interno de personal",
        "Transporte Urbano",
        "Recolección de basura",
        "Aduana interna",
        "Agente aduanal",
        "Servicios de consultoria",
        "Programa shelter",
        "Servicio Built to suit",
        "Reglamento interno",
        "Oficinas administrativas",
      ],
      newInfra: null,
      newRecords: null,
      roles: [],

      options: {
        u: false,
        d: false,
        i: false,
      },
    };
  },
  beforeMount() {
    setTimeout(() => {
      let params = new URLSearchParams();
      params.append("query", 1);
      params.append("id", this.$store.state.data.id_A);
      axios
        .post(`${this.$store.state.url}/getparquesusuarios`, params)
        .then((res) => {
          this.roles = res.data[0].permiso;
          console.log(res);
          let paramsD = new URLSearchParams();
          paramsD.append("id", res.data[0].persona);
          axios
            .post(`${this.$store.state.url}/getpark`, paramsD)
            .then((res) => {
              this.parque = res.data[0];
              this.getUserFromPark(res.data[0].id);
              this.getallnaves(res.data[0].id);
            })
            .catch((e) => console.log(e));
        })
        .catch((e) => console.log(e));
    }, 1000);
  },
  methods: {
    getTab() {
      switch (this.tab) {
        case 0:
          this.addNave = true;
      }
    },
    closeAction() {
      this.addUser = false;
      this.addNave = false;
    },
    addUserAction() {
      if (
        this.dataUser.name != "" &&
        this.dataUser.lastName != "" &&
        this.dataUser.last != "" &&
        this.dataUser.email != "" &&
        this.permiso != null
      ) {
        let params = new URLSearchParams();
        params.append(
          "fullname",
          `${this.dataUser.name} ${this.dataUser.lastName} ${this.dataUser.last}`
        );
        params.append("email", this.dataUser.email);

        var ctx = this;
        axios
          .post(`${this.$store.state.url}/createuser`, params)
          .then((res) => {
            if (res.data.message) {
              let par = new URLSearchParams();
              par.append("email", ctx.dataUser.email);
              par.append("pass", ctx.dataUser.password);
              axios
                .post(`${this.$store.state.url}/getuseridlogin`, par)
                .then((res) => {
                  ctx.createdataUser(res.data);
                })
                .catch((e) => console.log(e));
            }
          })
          .catch((e) => console.log(e));
      } else {
        Swal.fire({
          icon: "error",
          title: "Ooops ...",
          text: "Por favor asegurate de llenar todos los datos",
          backdrop: `
                  rgba(255,0,0,0.1)
                  url("/images/nyan-cat.gif")
                  left top
                  no-repeat
                `,
        });
      }
    },
    createdataUser(id) {
      let params = new URLSearchParams();
      params.append("id", id[0].id);
      params.append("charge", "AdministradorParque");
      params.append("habilitar", 1);
      params.append("key_corp", this.$store.state.data.key_corp);
      axios
        .post(`${this.$store.state.url}/createdatauser`, params)
        .then(() => {
          Swal.fire("Usuario agregado Puede cerrar esta pantalla");
        })
        .catch((e) => console.log(e));
      axios.get(
        `${this.$store.state.baseUrl}/mailler?email=${this.dataUser.email}&name=${this.dataUser.name}&link=${id[0].id}`
      );
      var paramsDos = new URLSearchParams();
      paramsDos.append("idUser", id[0].id);
      paramsDos.append("idParque", this.$store.state.parque);
      paramsDos.append("permiso", this.permiso);
      axios
        .post(`${this.$store.state.url}/setpermisos`, paramsDos)
        .then((res) => {
          console.log(res.data);
        })
        .catch((e) => console.log(e));
    },
    getUserFromPark(id) {
      let params = new URLSearchParams();
      params.append("query", 2);
      params.append("id_parque", id);
      axios
        .post(`${this.$store.state.url}/getparquesusuarios`, params)
        .then((res) => {
          this.users_parqs = res.data;
        })
        .catch((e) => console.log(e));
    },
    closePlusCard() {
      this.addNave = false;
      this.data_user = false;
      this.getallnaves(this.parque.key_corp);
      let timerInterval;
      Swal.fire({
        title: "Recuperando datos",
        timer: 1000,
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();
          timerInterval = setInterval(() => {
            const content = Swal.getHtmlContainer();
            if (content) {
              const b = content.querySelector("b");
              if (b) {
                b.textContent = Swal.getTimerLeft();
              }
            }
          }, 100);
        },
        willClose: () => {
          let data = new FormData();
          data.append("query", "perfil");
          data.append("uniqueName", this.$store.state.data.id);
          data.append("fichero_usuario", this.imgProfile);
          var config = {
            method: "post",
            url: `${this.$store.state.baseUrl}/api/uploadfiles`,
            headers: { "Content-Type": "image/jpeg" },
            data: data,
          };
          axios(config)
            .then(function(response) {
              console.log(JSON.stringify(response.data));
            })
            .catch(function(error) {
              console.log(error);
            });

          clearInterval(timerInterval);
        },
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log("I was closed by the timer");
        }
      });
    },
    openDialog(i) {
      this.data_user = true;
      this.data_to_info = i;
    },
    getallnaves(id) {
      let params = new URLSearchParams();
      params.append("id", id);
      axios
        .post(`${this.$store.state.url}/getallnaveforparks`, params)
        .then((res) => (this.naves = res.data))
        .catch((e) => console.log(e));
    },
    viewNave(id) {
      this.$store.commit("setNave", id);
      this.$router.push("/nave");
    },
    focs() {
      this.editUb = true;
    },
    updatePark() {
      let infra, records;

      if (this.newRecords != null) {
        records = this.newRecords;
      } else {
        records = this.parque.reconocimientoPracticas;
      }

      if (this.newInfra != null) {
        infra = this.newInfra;
      } else {
        infra = this.parque.ifraestructura;
      }

      let params = new URLSearchParams();
      params.append("id", this.parque.id);
      params.append("nombre_es", this.parque.nombre_es);
      params.append("nombre_en", this.parque.nombre_en);
      params.append("adminParq", this.parque.adminParq);
      params.append("parqProp", this.parque.parqProp);
      params.append("parqIntoParq", this.parque.parqIntoParq);
      params.append("region", this.parque.region);
      params.append("mercado", this.parque.mercado);
      params.append("tipoDeIndustria", this.parque.tipoDeIndustria);
      params.append("adminParq", this.parque.adminParq);
      params.append("tipoDePropiedad", this.parque.tipoDePropiedad);
      params.append("reconocimientoPracticas", records);
      params.append("ifraestructura", infra);
      params.append("contactName", this.parque.contactName);
      params.append("contactEmail", this.parque.contactEmail);
      params.append("numEmpleados", this.parque.numEmpleados);
      params.append("superficieTotal", this.parque.superficieTotal);
      params.append("date", null);

      axios
        .post(`${this.$store.state.url}/updatepark`, params)
        .then(() => {
          Swal.fire(
            "La informacion se actualizo esta en espera de que se habilite"
          );
        })
        .catch((e) => console.log(e));
    },
    back() {
      this.$ro;
    },
  },
  components: { plusCard },
  props: ["parque_id"],
  watch: {
    roles() {
      var roles = this.roles.split(",");
      var findValueEdit = roles.find((i) => i == "Editar");
      var findValueAdd = roles.find((i) => i == "Agregar");
      if (findValueEdit != undefined) {
        this.options.u = true;
      }

      if (findValueAdd != undefined) {
        this.options.i = true;
      }
    },
  },
};
</script>
