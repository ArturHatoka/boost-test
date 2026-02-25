# Frontend (`front`)

Vue 3 application (Composition API) with:

- `axios` HTTP layer
- `Element Plus` UI components
- Vite dev proxy to backend API
- Client table with create/edit/delete forms
- Inline status switch (Active/Inactive) without page reload

## Run

```bash
npm install
npm run dev
```

App runs on:

```text
http://localhost:5173
```

## Build

```bash
npm run build
```

## Environment

Use `.env.example` as reference:

- `VITE_API_BASE_URL` (default: `/api`)
- `VITE_DEV_PROXY_TARGET` (default: `http://localhost:8080`)

## Structure

- `src/shared/api/http.ts` - axios instance/interceptors
- `src/entities/client/api/clientApi.ts` - client API methods
- `src/features/client-list/model/useClientCrud.ts` - data and CRUD operations
- `src/features/client-list/model/useClientDialogs.ts` - dialog/form state
- `src/entities/client/ui/ClientFormFields.vue` - reusable client form fields
- `src/entities/client/ui/ClientTable.vue` - reusable clients table
- `src/widgets/client-api-preview/ui/ClientApiPreview.vue` - page-level composition
