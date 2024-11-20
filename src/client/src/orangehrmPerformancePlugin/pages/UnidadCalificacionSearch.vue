<template>
  <div class="orangehrm-background-container">
    <oxd-table-filter
      :filter-title="$t('unidadCalificacion.unidades_de_calificacion')"
    >
      <oxd-form @submit-valid="filterItems" @reset="resetDataTable">
        <oxd-form-row>
          <oxd-grid :cols="2" class="orangehrm-full-width-grid">
            <oxd-grid-item>
              <oxd-input
                v-model="filters.descripcion"
                :placeholder="$t('unidadCalificacion.descripcion')"
              />
            </oxd-grid-item>
            <oxd-grid-item>
              <oxd-select
                v-model="filters.estado"
                :options="estadoOptions"
                :placeholder="$t('unidadCalificacion.estado')"
              />
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>

        <oxd-divider />

        <oxd-form-actions>
          <oxd-button
            display-type="ghost"
            :label="$t('general.reset')"
            type="reset"
          />
          <oxd-button
            class="orangehrm-left-space"
            display-type="secondary"
            :label="$t('general.search')"
            type="submit"
          />
        </oxd-form-actions>
      </oxd-form>
    </oxd-table-filter>
    <br />
    <div class="orangehrm-paper-container">
      <div class="orangehrm-header-container">
        <oxd-button
          :label="$t('general.add')"
          icon-name="plus"
          display-type="secondary"
          @click="onClickAdd"
        />
      </div>
      <table-header
        :selected="checkedItems.length"
        :total="total"
        :loading="isLoading"
        @delete="onClickDeleteSelected"
      ></table-header>
      <div class="orangehrm-container">
        <oxd-card-table
          v-model:selected="checkedItems"
          v-model:order="sortDefinition"
          :headers="headers"
          :items="items?.data"
          :selectable="true"
          :clickable="false"
          :loading="isLoading"
          row-decorator="oxd-table-decorator-card"
        />
      </div>
      <div class="orangehrm-bottom-container">
        <oxd-pagination
          v-if="showPaginator"
          v-model:current="currentPage"
          :length="pages"
        />
      </div>
    </div>
    <delete-confirmation ref="deleteDialog"></delete-confirmation>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import DeleteConfirmationDialog from '@ohrm/components/dialogs/DeleteConfirmationDialog';
import { navigate } from '@ohrm/core/util/helper/navigation';
import { APIService } from '@/core/util/services/api.service';
import usePaginate from '@/core/util/composable/usePaginate';
import useSort from '@/core/util/composable/useSort';
import usei18n from '@/core/util/composable/usei18n';

const defaultFilters = {
  descripcion: null,
  estado: null,
};

const defaultSortOrder = {
  'unidadCalificacion.descripcion': 'ASC',
  'unidadCalificacion.valor': 'DEFAULT',
};

export default {
  name: 'UnidadCalificacionSearch',
  components: {
    'delete-confirmation': DeleteConfirmationDialog,
  },
  setup() {
    const { $t } = usei18n();

    const unidadCalificacionNormalizer = (data) => {
      return data.map((item) => {
        return {
          id: item.id,
          descripcion: item.descripcion,
          valor: item.valor,
          estado: item.estado ? $t('general.active') : $t('general.inactive'),
          isDeletable: item.isDeletable,
        };
      });
    };

    const filters = ref({ ...defaultFilters });

    const { sortDefinition, sortField, sortOrder, onSort } = useSort({
      sortDefinition: defaultSortOrder,
    });

    const serializedFilters = computed(() => {
      return {
        sortField: sortField.value,
        sortOrder: sortOrder.value,
        descripcion: filters.value.descripcion,
        estado: filters.value.estado,
      };
    });

    const http = new APIService(
      window.appGlobal.baseUrl,
      '/api/v2/performance/unidades-calificacion'
    );

    const {
      showPaginator,
      currentPage,
      total,
      pages,
      pageSize,
      response,
      isLoading,
      execQuery,
    } = usePaginate(http, {
      query: serializedFilters,
      normalizer: unidadCalificacionNormalizer,
    });

    onSort(execQuery);

    const estadoOptions = ref([
      { label: $t('general.active'), value: true },
      { label: $t('general.inactive'), value: false },
    ]);

    return {
      http,
      showPaginator,
      currentPage,
      total,
      pages,
      pageSize,
      items: response,
      isLoading,
      execQuery,
      filters,
      sortDefinition,
      estadoOptions,
    };
  },
  data() {
    return {
      headers: [
        {
          name: 'descripcion',
          title: this.$t('unidadCalificacion.descripcion'),
          sortField: 'unidadCalificacion.descripcion',
          style: { flex: '30%' },
        },
        {
          name: 'valor',
          title: this.$t('unidadCalificacion.valor'),
          sortField: 'unidadCalificacion.valor',
          style: { flex: '30%' },
        },
        {
          name: 'estado',
          title: this.$t('unidadCalificacion.estado'),
          style: { flex: '20%' },
        },
        {
          name: 'actions',
          slot: 'action',
          title: this.$t('general.actions'),
          style: { flex: '20%' },
          cellType: 'oxd-table-cell-actions',
          cellRenderer: this.cellRenderer,
        },
      ],
      checkedItems: [],
    };
  },
  methods: {
    cellRenderer(...[, , , row]) {
      const cellConfig = {};
      cellConfig.edit = {
        onClick: this.onClickEdit,
        props: {
          name: 'pencil-fill',
        },
      };
      if (row.isDeletable) {
        cellConfig.delete = {
          onClick: this.onClickDelete,
          component: 'oxd-icon-button',
          props: {
            name: 'trash',
          },
        };
      }
      return {
        props: {
          header: {
            cellConfig,
          },
        },
      };
    },
    onClickAdd() {
      navigate('/unidad-calificacion/save');
    },
    onClickDeleteSelected() {
      const ids = [];
      this.checkedItems.forEach((index) => {
        ids.push(this.items?.data[index].id);
      });
      this.$refs.deleteDialog.showDialog().then((confirmation) => {
        if (confirmation === 'ok') {
          this.deleteItems(ids);
        }
      });
    },
    onClickDelete(item) {
      this.$refs.deleteDialog.showDialog().then((confirmation) => {
        if (confirmation === 'ok') {
          this.deleteItems([item.id]);
        }
      });
    },
    deleteItems(items) {
      if (items instanceof Array) {
        this.isLoading = true;
        this.http
          .deleteAll({
            ids: items,
          })
          .then(() => {
            return this.$toast.deleteSuccess();
          })
          .then(() => {
            this.isLoading = false;
            this.resetDataTable();
          });
      }
    },
    onClickEdit(item) {
      navigate('/unidad-calificacion/edit/{id}', { id: item.id });
    },
    async filterItems() {
      await this.execQuery();
    },
    async resetDataTable() {
      this.checkedItems = [];
      await this.execQuery();
    },
  },
};
</script>
