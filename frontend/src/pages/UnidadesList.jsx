import { useEffect, useState } from "react";
import api from "../api/axios";
import { Link, useParams } from "react-router-dom";

export default function UnidadList() {
  const { proyectoId } = useParams();
  const [unidades, setUnidades] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchUnidades();
  }, []);

  const fetchUnidades = async () => {
    try {
      const response = await api.get(`/proyectos/${proyectoId}/unidades`);
      setUnidades(response.data);
      setLoading(false);
    } catch (error) {
      console.error("Error al obtener unidades:", error);
      setLoading(false);
    }
  };

  const handleEliminar = async (id) => {
    if (!confirm("¿Estás seguro de eliminar esta unidad?")) return;
    try {
      await api.delete(`/proyectos/${proyectoId}/unidades/${id}`);
      fetchUnidades();
    } catch (error) {
      alert("No se pudo eliminar la unidad");
    }
  };

  if (loading) return <p>Cargando unidades...</p>;

  return (
    <div className="max-w-5xl mx-auto p-6 bg-white rounded shadow">
      <h2 className="text-xl font-bold mb-4">Unidades del Proyecto</h2>
      <Link
        to={`/proyectos/${proyectoId}/unidades/nueva`}
        className="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block"
      >
        + Nueva Unidad
      </Link>

      <table className="w-full border-collapse">
        <thead>
          <tr className="bg-gray-200">
            <th className="p-2 border">Número Unidad</th>
            <th className="p-2 border">Tipo</th>
            <th className="p-2 border">Metraje (m²)</th>
            <th className="p-2 border">Precio Venta</th>
            <th className="p-2 border">Estado</th>
            <th className="p-2 border">Cliente Asociado</th>
            <th className="p-2 border">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {unidades.length === 0 ? (
            <tr>
              <td colSpan="7" className="p-4 text-center">No hay unidades disponibles</td>
            </tr>
          ) : (
            unidades.map((unidad) => (
              <tr key={unidad.id} className="hover:bg-gray-50">
                <td className="p-2 border">{unidad.numero_unidad}</td>
                <td className="p-2 border">{unidad.tipo_unidad}</td>
                <td className="p-2 border">{unidad.metraje_cuadrado}</td>
                <td className="p-2 border">${unidad.precio_venta.toLocaleString()}</td>
                <td className="p-2 border">{unidad.estado}</td>
                <td className="p-2 border">
                  {unidad.cliente ? unidad.cliente.nombre : "Sin cliente"}
                </td>
                <td className="p-2 border">
                  <Link
                    to={`/proyectos/${proyectoId}/unidades/${unidad.id}/editar`}
                    className="text-blue-600 hover:underline mr-4"
                  >
                    Editar
                  </Link>
                  <button
                    onClick={() => handleEliminar(unidad.id)}
                    className="text-red-600 hover:underline"
                  >
                    Eliminar
                  </button>
                </td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}
