<template>
  <componen>
    <!-- Arreglado los dialogs -->
    <v-card-title>
      <v-btn icon @click="close">
        <v-icon color="red"> mdi-arrow-left-bold</v-icon>
      </v-btn>
      <v-btn icon @click="getTabId" v-if="tab == 0">
        <v-icon>
          mdi-plus
        </v-icon>
      </v-btn>
      <!-- Usuario nuevo -->
      <v-dialog v-model="addUser" width="700" persistent>
        <plusCard :dialogs="5" @close="closePlusCard"></plusCard>
      </v-dialog>

      <!-- Parque nuevo-->
      <v-dialog v-model="addPark" width="700">
        <plusCard :dialogs="2" @close="closePlusCard"></plusCard>
      </v-dialog>

      <!-- Nave nueva-->
      <v-dialog v-model="addNav" width="700">
        <plusCard
          :dialogs="1"
          :id="this.$store.state.id_corp"
          @close="closePlusCard"
        ></plusCard>
      </v-dialog>
    </v-card-title>

    <v-tabs v-model="tab" background-color="transparent" color="basil" grow>
      <v-tab v-if="id.tipoDeSocio">
        Usuarios
      </v-tab>
      <v-tab v-if="id.tipoDeSocio != 'Patrocinador'">
        Parques
      </v-tab>
      <v-tab v-if="id.tipoDeSocio != 'Patrocinador'">
        Inquilinos
      </v-tab>
      <v-tab v-if="id.tipoDeSocio != 'Patrocinador'">
        Oferta de espacios
      </v-tab>
      <v-tab>
        Informacion
      </v-tab>
    </v-tabs>

    <!-- Items -->
    <v-tabs-items v-model="tab">
      <!-- Usuarios listo-->
      <v-tab-item>
        <v-container>
          <v-row>
            <v-col
              cols="12"
              sm="12"
              md="4"
              v-for="(i, key) in users"
              :key="key"
            >
              <v-card>
                <v-card-title v-text="i.fullname"></v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn icon @click="infoUserAction(i.id)">
                    <v-icon> mdi-eye </v-icon>
                  </v-btn>
                  <v-dialog
                    width="700"
                    v-model="cards.infoUser"
                    :retain-focus="false"
                  >
                    <InfoCard
                      :id="propsToComponents.user"
                      :type="'user'"
                      @closeInfo="closeInfo"
                    />
                  </v-dialog>
                  <v-btn icon @click="inactive('i', 'u', i.id)">
                    <v-icon> mdi-delete</v-icon>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>

      <!--Parques listo-->
      <v-tab-item v-if="id.tipoDeSocio != 'Patrocinador'">
        <v-container>
          <v-row>
            <v-col
              sm="12"
              md="4"
              v-for="i in parks"
              :key="i.id"
              v-show="i.nombre_es != 'standalone'"
            >
              <v-card>
                <span v-if="i.edit != 'null'"
                  >Ultima actualizacion : {{ i.edit }}</span
                >
                <v-card-title v-text="i.nombre_es"></v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    icon
                    @click="infoParkAction(i.id)"
                    v-if="i.edit != 'null'"
                  >
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>

                  <v-btn
                    icon
                    @click="infoParkAction(i.id)"
                    v-if="i.edit == 'null'"
                  >
                    <v-badge content="1" value="1" color="green" overlap>
                      <v-icon>mdi-eye</v-icon>
                    </v-badge>
                  </v-btn>

                  <v-dialog
                    v-model="cards.infoPark"
                    width="700"
                    :retain-focus="false"
                  >
                    <InfoCard
                      :id="propsToComponents.park[0]"
                      :type="'park'"
                      @closeInfo="closeInfo"
                    />
                  </v-dialog>
                  <v-btn icon>
                    <v-icon @click="inactive('i', 'p', i.id)"
                      >mdi-delete</v-icon
                    >
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>

      <!--Inquilinos-->
      <v-tab-item v-if="id.tipoDeSocio != 'Patrocinador'">
        <v-container>
          <v-row>
            <v-col sm="12" md="4" v-for="i in navs" :key="i.id">
              <v-card>
                <v-card-title v-text="i.name"></v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn icon @click="infoNaveAction(i.id)">
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                  <v-dialog
                    v-model="cards.infoNave"
                    persistent
                    width="700"
                    :retain-focus="false"
                  >
                    <InfoCard
                      :id="propsToComponents.nave"
                      :type="'nave'"
                      @closeInfo="closeInfo"
                    />
                  </v-dialog>
                  <v-btn icon @click="inactive('i', 'n', i.id)">
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>

      <!-- Espacios disponibles -->
      <v-tab-item v-if="id.tipoDeSocio != 'Patrocinador'">
        <v-container>
          <v-row>
            <v-col cols="12" md="4" v-for="(i, key) in places" :key="key">
              <v-card>
                <v-card-title>
                  Espacio disponible
                  <v-spacer>$ {{ i.precio_promedio }} Km2</v-spacer>
                </v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn icon @click="spaces = true">
                    <v-icon>mdi-eye</v-icon>
                  </v-btn>
                  <v-dialog v-model="spaces" width="700" :retain-focus="false">
                    <InfoCard :id="i" :type="'space'" @close="closeInfo" />
                  </v-dialog>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>

      <!--Info-->
      <v-tab-item>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.corporativo"
                label="Corporativo"
                outlined
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.nombre_es"
                label="Nombre en Español"
                outlined
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.nombre_en"
                label="Nombre en Inglés"
                outlined
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="id.rfc" label="RFC" outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <span>Dirección / Contacto</span>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.direccion"
                label="Calle y numero"
                outlined
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="id.cp" label="C.P" outlined>
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="id.colonia" label="Colonia" outlined>
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="id.estado" label="Estado" outlined>
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.municipio"
                label="Municipio/ Alcaldía"
                outlined
              >
              </v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="id.celular" label="Celular" outlined>
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <span>Inversiones</span>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.inversionAnualSiguiente"
                label="Inversión anual programada (pipeline año siguiente) MXN"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="12" md="6">
              <v-text-field
                v-model="id.inversionRealizadaActual"
                label="Inversión anual realizada (año en curso) MXN"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <v-text-field
                v-model="id.inversionRealizadaAnterior"
                label="Inversión anual realizada (año anterior) MXN"
                outlined
              >
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <span>Redes Sociales:</span>
            </v-col>
            <v-col cols="12" sm="12">
              <v-text-field
                label="Facebook"
                v-model="social.facebook"
                outlined
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <v-text-field
                label="Twiter"
                v-model="social.twiter"
                outlined
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <v-text-field
                label="LinkdIn"
                v-model="social.linkdin"
                outlined
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <v-text-field
                label="Instagram"
                v-model="social.instagram"
                outlined
              ></v-text-field>
            </v-col>

            <v-col cols="12" sm="12">
              <v-text-field
                disabled
                v-model="id.fechaDeValidacion"
                label="Ultima actualizacion"
                v-if="id.fechaDeValidacion"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="12">
              <v-btn
                text
                color="green"
                v-if="id.habilitar == 0"
                @click="saveInfoCorp"
                >habilitar</v-btn
              >
              <v-btn text color="red" v-if="id.habilitar == 0"
                >No Guardar</v-btn
              >
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>
    </v-tabs-items>
  </componen>
</template>
<script>
import axios from "axios";
import plusCard from "../components/plusCard";
import InfoCard from "../components/infoCard";
import Swal from "sweetalert2";

export default {
  data() {
    return {
      tab: 0,
      corp: [],
      parks: [],
      navs: [],
      tabs: [
        { title: "Corporativo", id: 1 },
        { title: "Usuarios", id: 2 },
        { title: "Parques", id: 3 },
        { title: "Naves", id: 4 },
      ],
      id: null,
      users: null,
      addUser: false,
      addPark: false,
      addNav: false,
      cards: {
        infoUser: false,
        infoPark: false,
        infoNave: false,
      },
      propsToComponents: {
        user: 0,
        park: 0,
        nave: 0,
      },
      social: {
        facebook: null,
        twiter: null,
        instagram: null,
        linkdin: null,
      },
      places: {},
      spaces: false,
    };
  },
  components: {
    plusCard,
    InfoCard,
  },
  methods: {
    saveInfoCorp() {
      let params = new URLSearchParams();
      params.append("id", this.id.id);
      params.append("nombre_es", this.id.nombre_es);
      params.append("nombre_en", this.id.nombre_en);
      params.append("direccion", this.id.direccion);
      params.append("cp", this.id.cp);
      params.append("colonia", this.id.colonia);
      params.append("estado", this.id.estado);
      params.append("municipio", this.id.municipio);
      params.append("telefono", this.id.celular);
      params.append("inversionAnualSiguiente", this.id.inversionAnualSiguiente);
      params.append("rfc", this.id.rfc);
      params.append("habilitar", 1);

      axios
        .post(`${this.$store.state.url}/updatecorp`, params)
        .then(() => {
          this.$router.push("/");
        })
        .catch((e) => console.log(e));
    },

    close() {
      this.$router.push("/");
    },

    getInfoCorpAction() {
      let params = new URLSearchParams();
      params.append("id", this.$store.state.id_corp);
      axios
        .post(`${this.$store.state.url}/corp`, params)
        .then((res) => {
          this.id = res.data;
          const ctx = this;
          let params = new URLSearchParams();
          params.append("id", res.data.id);
          console.log(res.data.id);
          console.log(this.id.id);
          axios
            .post(`${this.$store.state.url}/getuserbykeycorp`, params)
            .then((res) => {
              ctx.users = res.data;
            })
            .catch((e) => console.log(e));

          let paramsExtra = new URLSearchParams();
          paramsExtra.append("query", 1);
          paramsExtra.append("id", this.id.id);
          axios
            .post(`${this.$store.state.url}/extras`, paramsExtra)
            .then((res) => (this.social = JSON.parse(res.data.data)))
            .catch((e) => console.log(e));
        })
        .catch((e) => console.log(e));
      this.getCorpInfo = true;
    },

    getTabId() {
      switch (this.tab) {
        case 0:
          this.addUser = true;
          break;
        case 1:
          this.addPark = true;
          break;
        case 2:
          this.addNav = true;
          break;
      }
    },

    getParks() {
      let params = new URLSearchParams();
      params.append("id", this.$store.state.id_corp);
      axios
        .post(`${this.$store.state.url}/getallpark`, params)
        .then((res) => (this.parks = res.data))
        .catch((e) => console.log(e));
    },

    getAllNaves() {
      let params = new URLSearchParams();
      params.append("id", this.$store.state.id_corp);
      axios
        .post(`${this.$store.state.url}/getallnaveforpark`, params)
        .then((res) => (this.navs = res.data))
        .catch((e) => console.log(e));
    },

    inactive(type, table, id) {
      Swal.fire({
        title: "¿Esta seguro de esta accion?",
        text: "Esta apunto de Eliminar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          let params = new URLSearchParams();
          params.append("type", type);
          params.append("table", table);
          params.append("id", id);
          axios
            .post(`${this.$store.state.url}/activeinactive`, params)
            .then(() => {
              this.getInfoCorpAction();
              this.getParks();
              this.getAllNaves();
              let timerInterval;
              Swal.fire({
                title: "Recuperando informacion",
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
                  clearInterval(timerInterval);
                },
              }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                  console.log("...");
                }
              });
            })
            .catch((e) => console.log(e));
        }
      });
    },

    infoUserAction(id) {
      let params = new URLSearchParams();
      params.append("id", id);
      axios
        .post(`${this.$store.state.url}/getdatauser`, params)
        .then((res) => {
          this.propsToComponents.user = res.data;
        })
        .catch((e) => console.log(e));
      this.cards.infoUser = true;
    },

    infoParkAction(id) {
      let params = new URLSearchParams();
      params.append("id", id);
      axios
        .post(`${this.$store.state.url}/getpark`, params)
        .then((res) => {
          this.propsToComponents.park = res.data;
        })
        .catch((e) => console.log(e));
      this.cards.infoPark = true;
    },

    closeInfo() {
      this.cards.infoPark = false;
      this.cards.infoUser = false;
      this.cards.infoNave = false;
      this.cards.infoUser = false;
      this.spaces = false;
    },

    closePlusCard() {
      /* */
      this.getInfoCorpAction();
      this.getParks();
      this.getAllNaves();

      this.addUser = false;
      this.addPark = false;
      this.addNav = false;
      let timerInterval;
      Swal.fire({
        title: "Actualizando datos",
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
          clearInterval(timerInterval);
        },
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log("I was closed by the timer");
        }
      });
    },

    infoNaveAction(id) {
      let params = new URLSearchParams();
      params.append("id", id);
      axios
        .post(`${this.$store.state.url}/getinquilino`, params)
        .then((res) => {
          this.propsToComponents.nave = res.data[0];
        })
        .catch((e) => console.log(e));
      this.cards.infoNave = true;
    },

    getSpaces() {
      let params = new URLSearchParams();
      params.append("query", 1);
      params.append("id", this.$store.state.id_corp);
      axios
        .post(`${this.$store.state.url}/espacio`, params)
        .then((res) => {
          this.places = res.data;
        })
        .catch((e) => console.log(e));
    },
  },
  beforeMount() {
    this.getInfoCorpAction();
    this.getParks();
    this.getAllNaves();
    this.getSpaces();
  },
  computed: {
    imgRoute() {
      return `${this.$store.state.img}/`;
    },
  },
};
</script>
