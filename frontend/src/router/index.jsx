// src/router/index.jsx
import React from "react";
import { Routes, Route } from "react-router-dom";

import Login from "../pages/Login";
import ProyectoList from "../pages/ProyectoList";
import ProyectoForm from "../pages/ProyectoForm";
import UnidadesList from "../pages/UnidadesList";
import UnidadForm from "../pages/UnidadForm";
import PrivateRoute from "./PrivateRoute"; // Ruta protegida (wrapper)

export default function AppRouter() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      
      {/* Rutas protegidas */}
      <Route element={<PrivateRoute />}>
        <Route path="/proyectos" element={<ProyectoList />} />
        <Route path="/proyectos/nuevo" element={<ProyectoForm />} />
        <Route path="/proyectos/:id/editar" element={<ProyectoForm />} />
        <Route path="/proyectos/:proyectoId/unidades" element={<UnidadesList />} />
        <Route path="/proyectos/:proyectoId/unidades/nuevo" element={<UnidadForm />} />
        <Route path="/proyectos/:proyectoId/unidades/:unidadId/editar" element={<UnidadForm />} />
      </Route>

      {/* Ruta por defecto */}
      <Route path="*" element={<Login />} />
    </Routes>
  );
}
