import UnidadForm from "./UnidadForm";
import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import api from "../api/axios";
import "../styles/ProyectoForm.css";

export default function ProyectoForm() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    nombre: "",
    descripcion: "",
    ubicacion: "",
    fecha_inicio: "",
    fecha_fin: "",
    estado: ""
  });

  const [error, setError] = useState("");
  const [errores, setErrores] = useState({});
  const [loading, setLoading] = useState(false);
  const [unidades, setUnidades] = useState([]);
  const [modoUnidad, setModoUnidad] = useState(null);

  useEffect(() => {
    if (id) {
      setLoading(true);
      api.get(`/proyectos/${id}`)
        .then(res => {
          setFormData({
            nombre: res.data.nombre,
            descripcion: res.data.descripcion,
            ubicacion: res.data.ubicacion,
            fecha_inicio: res.data.fecha_inicio?.split('T')[0] || "",
            fecha_fin: res.data.fecha_fin?.split('T')[0] || "",
            estado: res.data.estado,
          });
          return api.get(`/proyectos/${id}/unidades`);
        })
        .then(res => {
          setUnidades(res.data);
          setLoading(false);
        })
        .catch(() => {
          setError("Error al cargar el proyecto o sus unidades");
          setLoading(false);
        });
    }
  }, [id]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrores({});
    setError("");

    const nuevosErrores = {};

    if (!formData.nombre.trim()) nuevosErrores.nombre = "El nombre es obligatorio";
    if (!formData.ubicacion.trim()) nuevosErrores.ubicacion = "La ubicación es obligatoria";
    if (!formData.estado) nuevosErrores.estado = "Debe seleccionar un estado";
    if (!formData.fecha_inicio) nuevosErrores.fecha_inicio = "Fecha de inicio obligatoria";
    if (!formData.fecha_fin) nuevosErrores.fecha_fin = "Fecha de fin obligatoria";

    if (
      formData.fecha_inicio &&
      formData.fecha_fin &&
      formData.fecha_fin < formData.fecha_inicio
    ) {
      nuevosErrores.fecha_fin = "La fecha de fin no puede ser anterior a la de inicio";
    }

    if (Object.keys(nuevosErrores).length > 0) {
      setErrores(nuevosErrores);
      return;
    }

    try {
      if (id) {
        await api.put(`/proyectos/${id}`, formData);
      } else {
        await api.post("/proyectos", formData);
      }
      navigate("/proyectos");
    } catch {
      setError("Error al guardar el proyecto");
    }
  };

  const handleGuardarUnidad = async (unidadFormateada) => {
    const token = localStorage.getItem("token");
    let clienteId = unidadFormateada.cliente_id || null;

    try {

      
      if (unidadFormateada.cliente) {
        setError("");
        const rut = unidadFormateada.cliente.rut;

        // Validar si el RUT ya existe
        const resValidacion = await api.get(`/clientes/validar-rut/${encodeURIComponent(rut)}`, {
          headers: { Authorization: `Bearer ${token}` }
        });

        if (!resValidacion.data.unico) {
          setError(`El RUT ${rut} ya está registrado en otro cliente.`);
          return; // Salir para no continuar con el guardado
        }

        // Crear cliente nuevo
        const resCliente = await fetch("/api/clientes", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${token}`,
            Accept: "application/json"
          },
          body: JSON.stringify(unidadFormateada.cliente)
        });

        if (!resCliente.ok) {
          const errorData = await resCliente.json();
          setError(errorData.message || "No se pudo crear el cliente");
          return;
        }

        const clienteData = await resCliente.json();
        clienteId = clienteData.id;
      }

      // Preparar payload para unidad
      const payload = {
        ...unidadFormateada,
        cliente_id: clienteId,
      };
      delete payload.cliente;

      if (clienteId === null && unidadFormateada.cliente) {
        setError("No se puede guardar la unidad sin un cliente válido");
        return;
      }

      if (payload.id) {
        await api.put(`/proyectos/${id}/unidades/${payload.id}`, payload);
        // res = await fetch(`/api/proyectos/${proyectoId}/unidades/${unidad.id}`, {
      } else {
        await api.post(`/proyectos/${id}/unidades`, payload);
      }

      // Refrescar lista de unidades
      const res = await api.get(`/proyectos/${id}/unidades`);
      setUnidades(res.data);
      setModoUnidad(null);
      setError("");
    } catch (err) {
      console.error("Error guardando unidad:", err);
      setError("Error al guardar la unidad");
    }
  };

  // Nueva función para eliminar unidad
  const handleEliminarUnidad = async (unidadId) => {
    if (!window.confirm("¿Estás seguro de eliminar esta unidad?")) return;
    try {
      await api.delete(`/proyectos/${id}/unidades/${unidadId}`);
      // Actualizar la lista sin la unidad eliminada
      setUnidades(unidades.filter(u => u.id !== unidadId));
      setError("");
    } catch (err) {
      console.error("Error eliminando unidad:", err);
      setError("No se pudo eliminar la unidad");
    }
  };

  if (loading) return <p className="loading">Cargando...</p>;

  return (
    <div className="form-container">
      <h2 className="form-title">{id ? "Editar Proyecto" : "Nuevo Proyecto"}</h2>

      {error && <p className="error-msg">{error}</p>}

      <form onSubmit={handleSubmit} className="form">
        <label htmlFor="nombre">Nombre</label>
        <input
          type="text"
          name="nombre"
          value={formData.nombre}
          onChange={handleChange}
          required
          placeholder="Nombre del proyecto"
        />
        {errores.nombre && <div className="popup-error">{errores.nombre}</div>}

        <label htmlFor="descripcion">Descripción</label>
        <textarea
          name="descripcion"
          value={formData.descripcion}
          onChange={handleChange}
          placeholder="Descripción detallada"
          rows={4}
        />

        <label htmlFor="ubicacion">Ubicación</label>
        <input
          type="text"
          name="ubicacion"
          value={formData.ubicacion}
          onChange={handleChange}
          required
          placeholder="Ubicación"
        />
        {errores.ubicacion && <div className="popup-error">{errores.ubicacion}</div>}

        <label htmlFor="fecha_inicio">Fecha Inicio</label>
        <input
          type="date"
          name="fecha_inicio"
          value={formData.fecha_inicio}
          onChange={handleChange}
        />
        {errores.fecha_inicio && <div className="popup-error">{errores.fecha_inicio}</div>}

        <label htmlFor="fecha_fin">Fecha Fin</label>
        <input
          type="date"
          name="fecha_fin"
          value={formData.fecha_fin}
          onChange={handleChange}
        />
        {errores.fecha_fin && <div className="popup-error">{errores.fecha_fin}</div>}

        <label htmlFor="estado">Estado</label>
        <select
          name="estado"
          value={formData.estado}
          onChange={handleChange}
          required
        >
          <option value="">Seleccione</option>
          <option value="En construcción">En construcción</option>
          <option value="Terminado">Terminado</option>
          <option value="En pausa">En pausa</option>
          <option value="Cancelado">Cancelado</option>
        </select>
        {errores.estado && <div className="popup-error">{errores.estado}</div>}

        <button type="submit" className="form-button">Guardar Proyecto</button>
      </form>

      {id && (
        <>
          <hr className="separator" />
          <h3>Unidades del Proyecto</h3>

          {modoUnidad ? (
            <UnidadForm
              proyectoId={id}
              unidad={modoUnidad === "nuevo" ? null : modoUnidad}
              onCancel={() => setModoUnidad(null)}
              onSave={handleGuardarUnidad}
              permitirNuevoCliente={true}
            />
          ) : (
            <>
              <button
                onClick={() => setModoUnidad("nuevo")}
                className="form-button mt-2"
              >
                Agregar Unidad
              </button>
              <div className="unidades-grid">
                {unidades.length === 0 ? (
                  <p>No hay unidades asociadas</p>
                ) : (
                  unidades.map(u => (
                    <div key={u.id} className="unidad-card">
                      <h4>Unidad {u.numero_unidad}</h4>
                      <p><strong>Tipo:</strong> {u.tipo_unidad}</p>
                      <p><strong>Precio:</strong> UF {parseFloat(u.precio_venta).toLocaleString("es-CL")}</p>
                      <p><strong>Estado:</strong> {u.estado}</p>
                      <p><strong>Cliente:</strong> {u.cliente?.nombre || "Sin cliente"}</p>
                      <button onClick={() => setModoUnidad(u)} className="btn-editar-unidad">Editar</button>
                      <button 
                        onClick={() => handleEliminarUnidad(u.id)} 
                        className="btn-eliminar-unidad" 
                        style={{marginLeft: "8px", backgroundColor: "red", color: "white"}}
                      >
                        Eliminar
                      </button>
                    </div>
                  ))
                )}
              </div>
            </>
          )}
        </>
      )}

      <button onClick={() => navigate("/proyectos")} className="btn-volver">
        ← Volver al listado
      </button>
    </div>
  );
}
