<template>
  <div class="layers">
    <div class="layer w-100 p-20 bd bgc-white">
      <span class="fsz-lg" style="font-weight:lighter">Team</span>
      <div class="layer w-100 mT-20">
        <div
          v-for="(row,index) in agentCluster.pagelayer.team.employee_grid.rows"
          :key="row.id"
          class="row"
        >
          <div v-for="(col,index1) in 6" :key="col.id" class="col-md-2">
            <div
              v-if="agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1]"
              class="image-thumb-container"
            >
              <div class="image-thumb-container-inside">
                <div
                  v-if="agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].image"
                  class="image-thumb"
                  :style="'background-image:url('+agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].image+');'"
                  style="border:5px white solid"
                >
                  <div style="position:absolute;bottom:-5px;right:-10px">
                    <span
                      class="badge badge-pill bgc-red-500 c-white"
                    >{{ getPositionInitials(agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].access) }}</span>
                  </div>
                </div>
                <div
                  v-else
                  class="image-thumb bgc-white text-center"
                  style="border:5px white solid;margin:0 auto"
                >
                  <span
                    class="fsz-xl c-grey-500"
                    style="font-weight:lighter"
                  >{{ agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].first_name[0]+agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].last_name[0] }}</span>
                  <div style="position:absolute;bottom:-5px;right:-10px">
                    <span
                      class="badge badge-pill bgc-red-500 c-white"
                    >{{ getPositionInitials(agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].access) }}</span>
                  </div>
                </div>
                <div class="pY-20 text-center image-thumb-label">
                  <div
                    class="w-100 fsz-sm fw-600 image-thumb-text"
                  >{{ agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].fullname }}</div>
                  <div
                    class="w-100 fsz-xs image-thumb-text"
                  >{{ split(agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].mail,'@',0) }}</div>
                  <div class="w-100 fsz-xs image-thumb-text">@cnmsolutions.net</div>
                </div>
                <div
                  class="w-100 text-center align-self-center image-thumb-stats-container"
                  data-toggle="tooltip"
                  title="Days employed"
                  data-placement="bottom"
                >
                  <div
                    class="fw-900 fsz-xl image-thumb-text"
                  >{{ getEmployedDays(agentCluster.pagelayer.team.employee_grid.array[((index+1)*6)-(6-(index1+1))-1].hired_date) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="layer p-20 w-100"></div>
    </div>
  </div>
</template>
<style>
.image-thumb {
  position: relative;
  border: 1px #f44336 solid;
  width: 80px;
  height: 80px;
  margin: 0 auto;
  border-radius: 50%;
  background-size: cover;
  background-position: center center;
  /* transition: ease-in-out 0.5s; */
  /* animation: fadein 2s; */
  /* -webkit-box-shadow: 0 10px 6px -6px #777;
  -moz-box-shadow: 0 10px 6px -6px #777;
  box-shadow: 0 10px 6px -6px #777; */
}
.image-thumb-container {
  position: relative;
  border-radius: 5px;
}
.image-thumb-container:before {
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.image-thumb-container:hover {
  background-size: cover;
  background-position: center right;
  background-color: #f44336;
  background-blend-mode: multiply;
  opacity: 0.9;
}

.image-thumb-container-inside {
  position: relative;
  padding: 10px;
  border-radius: 5px;
  /* filter: blur(2px); */
  /* border: 1px solid rgba(0, 0, 0, 0.0625); */
  -webkit-box-shadow: 0 10px 6px -6px #777;
  -moz-box-shadow: 0 10px 6px -6px #777;
  box-shadow: 0 10px 6px -6px #777;
  transition: ease-in 0.2s;
}

.image-thumb-container-inside:hover .image-thumb {
  border: white 5px solid;
  transition: ease-in 0.2s;
}

.image-thumb-container-inside:hover .image-thumb-label .image-thumb-text {
  color: white;
  transition: ease-in 0.2s;
}
.image-thumb-container-inside:hover
  .image-thumb-stats-container
  .image-thumb-text {
  color: white;
  transition: ease-in 0.2s;
}
.image-thumb-container-inside:hover .image-thumb-stats-container {
  border-radius: 10px;
  background-color: #d32f2f;
  transition: ease-in 0.2s;
}
</style>

<script>
import moment from "moment";
export default {
  props: ["userId", "accessId"],
  data() {
    return {
      agentCluster: {
        pagelayer: {
          team: {
            employee_grid: {
              rows: 0,
              array: [],
              hover: [],
              image: []
            }
          }
        }
      }
    };
  },
  mounted() {
    // this.broadcastListerner();
  },
  created() {
    this.fetchAgentTeam();
  },
  methods: {
    broadcastListerner: function() {
      Echo.private("workingAgent." + this.userId).listen("StartWork", e => {
        // console.log(e);
      });
    },
    fetchAgentTeam: function() {
      let pageurl = "/api/v1/users/cluster/" + this.userId;
      fetch(pageurl)
        .then(res => res.json())
        .then(res => {
          console.log(res.meta.options);
          let sorted = [];
          sorted.push(res.meta.options.filter(this.filterOperationsManager)[0]);
          sorted.push(res.meta.options.filter(this.filterTeamLeader)[0]);
          // sorted.push(res.meta.options.filter(this.filterAgents));
          let temp = res.meta.options.filter(this.filterAgents);
          temp.forEach(
            function(v, i) {
              sorted.push(v);
            }.bind(this)
          );
          this.agentCluster.pagelayer.team.employee_grid.array = sorted;
          sorted.forEach(
            function(v, i) {
              this.agentCluster.pagelayer.team.employee_grid.hover.push(false);
              this.agentCluster.pagelayer.team.employee_grid.image.push(
                v.image
              );
            }.bind(this)
          );
          this.agentCluster.pagelayer.team.employee_grid.rows = Math.ceil(
            sorted.length / 6
          );
          console.log(this.agentCluster.pagelayer.team.employee_grid.rows);
          console.log(sorted);

          //   alert(rows);
        })
        .catch(err => console.log(err));
    },
    filterTeamLeader: function(index) {
      return index.access == 16;
    },
    filterOperationsManager: function(index) {
      return index.access == 15;
    },
    filterAgents: function(index) {
      return index.access == 17;
    },
    getEmployedDays: function(hired_date) {
      let hd = hired_date.split("/");
      let now = moment(moment(), "YYYY-MM-DD"),
        from = moment([hd[2], hd[0], hd[1]], "YYYY-MM-DD");
      return now.diff(from, "days");
    },
    getPositionInitials: function(access) {
      let result;
      if (access == 17) {
        result = "OP";
      } else if (access == 16) {
        result = "TL";
      } else if (access == 15) {
        result = "OM";
      }
      return result;
    }
  }
};
</script>
