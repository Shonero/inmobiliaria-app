import { useEffect, useState } from "react";
import api from "../api/axios";
import { Link, useNavigate } from "react-router-dom";
import "../styles/ProyectoList.css";


export default function ProyectoList() {
  const [proyectos, setProyectos] = useState([]);
  const [busqueda, setBusqueda] = useState("");
  const [pagina, setPagina] = useState(1);
  const [totalPaginas, setTotalPaginas] = useState(1);
  const navigate = useNavigate();

  useEffect(() => {
    fetchProyectos();
  }, [busqueda, pagina]);

  const fetchProyectos = async () => {
    try {
      const response = await api.get("/proyectos", {
        params: { search: busqueda, page: pagina },
      });
      setProyectos(response.data.data);
      setTotalPaginas(response.data.last_page);
    } catch (error) {
      console.error("Error al obtener proyectos:", error);
    }
  };

  const handleEliminar = async (id) => {
    if (!confirm("¿Estás seguro de que quieres eliminar este proyecto?")) return;
    try {
      await api.delete(`/proyectos/${id}`);
      fetchProyectos();
    } catch (error) {
      console.error("Error al eliminar proyecto:", error);
      alert("No se pudo eliminar el proyecto.");
    }
  };

  const handleLogout = () => {
    localStorage.removeItem("token");
    navigate("/login");
  };

  return (
  <div className="container">
    <div className="header">
      <h1>Proyectos Inmobiliarios</h1>
      <button onClick={handleLogout} className="logout-btn">Cerrar sesión</button>
    </div>

    <div className="search-add">
      <input
        type="text"
        placeholder="Buscar por nombre, ubicación..."
        className="search-input"
        value={busqueda}
        onChange={(e) => setBusqueda(e.target.value)}
      />
      <Link to="/proyectos/nuevo" className="add-btn">+ Nuevo Proyecto</Link>
    </div>

    <div className="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Ubicación</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {proyectos.map((proyecto) => (
            <tr key={proyecto.id}>
              <td>{proyecto.nombre}</td>
              <td>{proyecto.ubicacion}</td>
              <td>{proyecto.estado}</td>
              <td>
                <Link to={`/proyectos/${proyecto.id}/editar`} className="action-link">Editar</Link>
                <button onClick={() => handleEliminar(proyecto.id)} className="action-btn">Eliminar</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>

    <div className="pagination">
      <button disabled={pagina === 1} onClick={() => setPagina(pagina - 1)}>Anterior</button>
      <span>Página {pagina} de {totalPaginas}</span>
      <button disabled={pagina === totalPaginas} onClick={() => setPagina(pagina + 1)}>Siguiente</button>
    </div>
  </div>
);

}
