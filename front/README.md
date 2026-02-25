# Frontend (`front`)

Vue 3 application (Composition API) with:

- `axios` HTTP layer
- `Element Plus` UI components
- Vite dev proxy to backend API

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
- `src/features/client-list/model/useClientList.ts` - Composition API logic
- `src/widgets/client-api-preview/ui/ClientApiPreview.vue` - UI on Element Plus
