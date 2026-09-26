const API_URL = import.meta.env.VITE_API_URL ?? "http://127.0.0.1:8000/api";

export async function apiFetch(path, { method = "GET", body, token } = {}) {
  const headers = { Accept: "application/json" };
  if (body) headers["Content-Type"] = "application/json";
  if (token) headers.Authorization = `Bearer ${token}`;

  const res = await fetch(`${API_URL}${path}`, {
    method,
    headers,
    body: body ? JSON.stringify(body) : undefined,
  });

  const data = await res.json().catch(() => null);

  if (!res.ok) {
    const error = new Error(
      data?.message ?? "No se pudo conectar con el servidor.",
    );
    error.status = res.status;
    error.errors = data?.errors ?? {};
    throw error;
  }

  return data;
}
