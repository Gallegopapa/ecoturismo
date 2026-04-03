import React, { useState, useEffect } from "react";
import { companyService } from "../services/api";
import RejectReservationModal from "./RejectReservationModal";
import "./CompanyDashboard.css";

const CompanyDashboard = () => {
    const [reservations, setReservations] = useState([]);
    const [stats, setStats] = useState({
        pendientes: 0,
        aceptadas: 0,
        rechazadas: 0,
        total: 0,
    });
    const [loading, setLoading] = useState(true);
    const [filter, setFilter] = useState("pendiente");
    const [selectedReservation, setSelectedReservation] = useState(null);
    const [rejectionReasons, setRejectionReasons] = useState([]);
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [message, setMessage] = useState("");
    const [messageType, setMessageType] = useState("");
    const [rejecting, setRejecting] = useState(false);
    const [placesManaged, setPlacesManaged] = useState([]);
    const [selectedPlaceId, setSelectedPlaceId] = useState("");
    const [schedules, setSchedules] = useState([]);
    const [scheduleLoading, setScheduleLoading] = useState(false);
    const [newSchedule, setNewSchedule] = useState({
        dia_semana: "lunes",
        hora_inicio: "08:00",
        hora_fin: "17:00",
        activo: true,
    });

    const [placeForm, setPlaceForm] = useState({
        name: "",
        location: "",
        description: "",
        latitude: "",
        longitude: "",
        telefono: "",
        email: "",
        sitio_web: "",
        image: null,
    });
    const [placeImagePreview, setPlaceImagePreview] = useState("");
    const [localImagePreview, setLocalImagePreview] = useState("");
    const [placeLoading, setPlaceLoading] = useState(false);
    const [placeSaving, setPlaceSaving] = useState(false);
    const [placeDeleting, setPlaceDeleting] = useState(false);

    // Mapeo de imÃ¡genes locales (mismo que en places/detail/page.jsx)
    const mapeoImagenesDeterministico = {
        "Lago De La Pradera": "/imagenes/Lago.jpeg",
        "La Laguna Del OtÃºn": "/imagenes/laguna.jpg",
        "Laguna Del OtÃºn": "/imagenes/laguna.jpg",
        "Chorros De Don Lolo": "/imagenes/lolo-2.jpg",
        "Termales de Santa Rosa": "/imagenes/termaales.jpg",
        "Parque AcuÃ¡tico Consota": "/imagenes/consota.jpg",
        "Balneario Los Farallones": "/imagenes/farallones.jpeg",
        "Cascada Los Frailes": "/imagenes/frailes3.jpg",
        "RÃ­o San JosÃ©": "/imagenes/sanjose3.jpg",
        "Rio San Jose": "/imagenes/sanjose3.jpg",
        "Alto Del Nudo": "/imagenes/nudo.jpg",
        "Alto Del Toro": "/imagenes/toro.jpg",
        "La Divisa De Don Juan": "/imagenes/divisa3.jpeg",
        "Cerro Batero": "/imagenes/batero.jpg",
        "Reserva Forestal La Nona": "/imagenes/lanona5.jpg",
        "Reserva Natural Cerro Gobia": "/imagenes/gobia.jpg",
        "KaukitÃ¡ Bosque Reserva": "/imagenes/kaukita3.jpg",
        "Kaukita Bosque Reserva": "/imagenes/kaukita3.jpg",
        "Reserva Natural DMI Agualinda": "/imagenes/distritomanejo8.jpg",
        "Parque Nacional Natural TatamÃ¡": "/imagenes/tatama.jpg",
        "Parque Nacional Natural Tatama": "/imagenes/tatama.jpg",
        "Parque Las Araucarias": "/imagenes/araucarias.jpg",
        "Parque Regional Natural Cuchilla de San Juan":
            "/imagenes/cuchilla.jpg",
        "Parque Natural Regional Santa Emilia": "/imagenes/santaemilia2.jpg",
        "JardÃ­n BotÃ¡nico UTP": "/imagenes/jardin.jpeg",
        "Jardin Botanico UTP": "/imagenes/jardin.jpeg",
        "JardÃ­n BotÃ¡nico De Marsella": "/imagenes/jardinmarsella2.jpg",
        "Jardin Botanico De Marsella": "/imagenes/jardinmarsella2.jpg",
    };

    const normalizarNombre = (str) => {
        if (!str) return "";
        return str
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/\s+/g, " ")
            .trim();
    };

    const mapeoImagenesLocales = {
        "lago de la pradera": "/imagenes/Lago.jpeg",
        "la laguna del otÃºn": "/imagenes/laguna.jpg",
        "laguna del otÃºn": "/imagenes/laguna.jpg",
        "chorros de don lolo": "/imagenes/lolo-2.jpg",
        "termales de santa rosa": "/imagenes/termaales.jpg",
        "parque acuÃ¡tico consota": "/imagenes/consota.jpg",
        "balneario los farallones": "/imagenes/farallones.jpeg",
        "cascada los frailes": "/imagenes/frailes3.jpg",
        "rÃ­o san josÃ©": "/imagenes/sanjose3.jpg",
        "rio san jose": "/imagenes/sanjose3.jpg",
        "alto del nudo": "/imagenes/nudo.jpg",
        "alto del toro": "/imagenes/toro.jpg",
        "la divisa de don juan": "/imagenes/divisa3.jpeg",
        "cerro batero": "/imagenes/batero.jpg",
        "reserva forestal la nona": "/imagenes/lanona5.jpg",
        "reserva natural cerro gobia": "/imagenes/gobia.jpg",
        "kaukita bosque reserva": "/imagenes/kaukita3.jpg",
        "kaukitÃ¡ bosque reserva": "/imagenes/kaukita3.jpg",
        "reserva natural dmi agualinda": "/imagenes/distritomanejo8.jpg",
        "parque nacional natural tatamÃ¡": "/imagenes/tatama.jpg",
        "parque nacional natural tatama": "/imagenes/tatama.jpg",
        "parque las araucarias": "/imagenes/araucarias.jpg",
        "parque regional natural cuchilla de san juan":
            "/imagenes/cuchilla.jpg",
        "parque natural regional santa emilia": "/imagenes/santaemilia2.jpg",
        "jardÃ­n botÃ¡nico utp": "/imagenes/jardin.jpeg",
        "jardin botanico utp": "/imagenes/jardin.jpeg",
        "jardÃ­n botÃ¡nico de marsella": "/imagenes/jardinmarsella2.jpg",
        "jardin botanico de marsella": "/imagenes/jardinmarsella2.jpg",
    };

    const getPeopleCount = (reservation) => {
        return (
            reservation?.cantidad_personas ??
            reservation?.personas ??
            reservation?.numero_personas
        );
    };

    const normalizeImageUrl = (url) => {
        if (!url) return "";
        const value = String(url);
        if (value.startsWith("http://") || value.startsWith("https://"))
            return value;
        if (value.startsWith("//"))
            return `${window.location.protocol}${value}`;
        const withSlash = value.startsWith("/") ? value : `/${value}`;
        return `${window.location.origin}${withSlash}`;
    };

    const getPlaceDisplayImage = (placeName, apiImage) => {
        const nombreOriginal = placeName || "";
        const nombreLugar = normalizarNombre(nombreOriginal);
        const imagenSubida =
            apiImage &&
                (apiImage.includes("/storage/places/") ||
                    apiImage.startsWith("/storage/") ||
                    apiImage.includes("storage/places") ||
                    apiImage.startsWith("/imagenes/") ||
                    (apiImage.startsWith("http") &&
                        (apiImage.includes("/storage/places/") ||
                            apiImage.includes("/imagenes/"))))
                ? apiImage
                : null;

        let imagenLocal = null;
        if (!imagenSubida) {
            imagenLocal = mapeoImagenesDeterministico[nombreOriginal];
            if (!imagenLocal) {
                imagenLocal = mapeoImagenesLocales[nombreLugar];
            }
        }

        const finalImage = imagenSubida || imagenLocal || apiImage || "";
        return normalizeImageUrl(finalImage);
    };

    useEffect(() => {
        loadReservations();
        loadStats();
        loadRejectionReasons();
    }, [filter]);

    useEffect(() => {
        loadPlacesManaged();
    }, []);

    useEffect(() => {
        if (selectedPlaceId) {
            loadSchedules(selectedPlaceId);
            loadPlaceDetails(selectedPlaceId);
        }
    }, [selectedPlaceId]);

    const loadReservations = async () => {
        try {
            setLoading(true);
            const data = await companyService.reservations.getAll({
                estado: filter,
            });
            setReservations(data.data || []);
        } catch (error) {
            console.error("Error cargando reservas:", error);
            showMessage("Error al cargar reservas", "error");
        } finally {
            setLoading(false);
        }
    };

    const loadRejectionReasons = async () => {
        try {
            const data = await companyService.rejectionReasons.getAll();
            setRejectionReasons(data || []);
        } catch (error) {
            console.error("Error cargando razones de rechazo:", error);
        }
    };

    const loadStats = async () => {
        try {
            const data = await companyService.reservations.getStatsSummary();
            setStats({
                pendientes: data?.pendientes || 0,
                aceptadas: data?.aceptadas || 0,
                rechazadas: data?.rechazadas || 0,
                total: data?.total || 0,
            });
        } catch (error) {
            console.error("Error cargando estadisticas:", error);
        }
    };

    const loadPlacesManaged = async () => {
        try {
            const data = await companyService.places.getAll();

            const list = Array.isArray(data) ? data : [];
            setPlacesManaged(list);
            if (list.length > 0) {
                setSelectedPlaceId(String(list[0].id));
            } else {
                setSelectedPlaceId("");
            }
        } catch (error) {
            console.error("Error cargando lugares gestionados:", error);
        }
    };

    const loadPlaceDetails = async (placeId) => {
        try {
            setPlaceLoading(true);
            const data = await companyService.places.getById(placeId);
            setPlaceForm({
                name: data.name || "",
                location: data.location || "",
                description: data.description || "",
                latitude: data.latitude ?? "",
                longitude: data.longitude ?? "",
                telefono: data.telefono || "",
                email: data.email || "",
                sitio_web: data.sitio_web || "",
                image: null,
            });
            const imageValue =
                data.image || data.imagen || data.image_url || data.foto || "";
            setPlaceImagePreview(getPlaceDisplayImage(data.name, imageValue));
            setLocalImagePreview("");
        } catch (error) {
            console.error("Error cargando lugar:", error);
            showMessage("Error al cargar lugar", "error");
        } finally {
            setPlaceLoading(false);
        }
    };

    const normalizeTime = (value) => {
        if (!value) return "";
        const timeStr = String(value);
        return timeStr.length >= 5 ? timeStr.slice(0, 5) : timeStr;
    };

    const loadSchedules = async (placeId) => {
        try {
            setScheduleLoading(true);
            const data = await companyService.places.schedules.getAll(placeId);
            const normalized = Array.isArray(data)
                ? data.map((item) => ({
                    ...item,
                    hora_inicio: normalizeTime(item.hora_inicio),
                    hora_fin: normalizeTime(item.hora_fin),
                }))
                : [];
            setSchedules(normalized);
        } catch (error) {
            console.error("Error cargando horarios:", error);
            showMessage("Error al cargar horarios", "error");
        } finally {
            setScheduleLoading(false);
        }
    };

    const handleScheduleChange = (scheduleId, field, value) => {
        const nextValue = field.includes("hora_")
            ? normalizeTime(value)
            : value;
        setSchedules((prev) =>
            prev.map((item) =>
                item.id === scheduleId
                    ? { ...item, [field]: nextValue, _dirty: true }
                    : item,
            ),
        );
    };

    const handleSaveSchedule = async (schedule) => {
        if (!selectedPlaceId) return;

        try {
            setScheduleLoading(true);
            await companyService.places.schedules.update(
                selectedPlaceId,
                schedule.id,
                {
                    dia_semana: schedule.dia_semana,
                    hora_inicio: normalizeTime(schedule.hora_inicio),
                    hora_fin: normalizeTime(schedule.hora_fin),
                    activo: schedule.activo,
                },
            );
            showMessage("Horario actualizado", "success");
            loadSchedules(selectedPlaceId);
        } catch (error) {
            console.error("Error actualizando horario:", error);
            showMessage("Error al actualizar horario", "error");
        } finally {
            setScheduleLoading(false);
        }
    };

    const handleDeleteSchedule = async (scheduleId) => {
        if (!selectedPlaceId) return;

        if (!window.confirm("Deseas eliminar este horario?")) {
            return;
        }

        try {
            setScheduleLoading(true);
            await companyService.places.schedules.delete(
                selectedPlaceId,
                scheduleId,
            );
            showMessage("Horario eliminado", "success");
            loadSchedules(selectedPlaceId);
        } catch (error) {
            console.error("Error eliminando horario:", error);
            showMessage("Error al eliminar horario", "error");
        } finally {
            setScheduleLoading(false);
        }
    };

    const handleAddSchedule = async () => {
        if (!selectedPlaceId) return;

        try {
            setScheduleLoading(true);
            await companyService.places.schedules.create(
                selectedPlaceId,
                newSchedule,
            );
            showMessage("Horario creado", "success");
            setNewSchedule({
                dia_semana: "lunes",
                hora_inicio: "08:00",
                hora_fin: "17:00",
                activo: true,
            });
            loadSchedules(selectedPlaceId);
        } catch (error) {
            console.error("Error creando horario:", error);
            showMessage("Error al crear horario", "error");
        } finally {
            setScheduleLoading(false);
        }
    };

    const handleAllDay = (scheduleId) => {
        handleScheduleChange(scheduleId, "hora_inicio", "00:00");
        handleScheduleChange(scheduleId, "hora_fin", "23:59");
    };

    const handlePlaceChange = (field, value) => {
        setPlaceForm((prev) => ({ ...prev, [field]: value }));
    };

    const handlePlaceImageChange = (file) => {
        setPlaceForm((prev) => ({ ...prev, image: file }));
        if (file) {
            const previewUrl = URL.createObjectURL(file);
            setLocalImagePreview(previewUrl);
        } else {
            setLocalImagePreview("");
        }
    };

    const handlePlaceSave = async () => {
        if (!selectedPlaceId) return;
        if (!placeForm.name.trim()) {
            showMessage("El nombre es obligatorio", "error");
            return;
        }

        try {
            setPlaceSaving(true);
            await companyService.places.update(selectedPlaceId, placeForm);
            showMessage("Lugar actualizado correctamente", "success");
            loadPlacesManaged();
            loadPlaceDetails(selectedPlaceId);
            setLocalImagePreview("");
        } catch (error) {
            console.error("Error actualizando lugar:", error);
            const errorMessage =
                error.response?.data?.message || "Error al actualizar lugar";
            showMessage(errorMessage, "error");
        } finally {
            setPlaceSaving(false);
        }
    };

    const handlePlaceDelete = async () => {
        if (!selectedPlaceId) return;
        if (
            !window.confirm(
                "Â¿Seguro que deseas eliminar este lugar? Esta acciÃ³n no se puede deshacer.",
            )
        ) {
            return;
        }

        try {
            setPlaceDeleting(true);
            await companyService.places.delete(selectedPlaceId);
            showMessage("Lugar eliminado correctamente", "success");
            await loadPlacesManaged();
        } catch (error) {
            console.error("Error eliminando lugar:", error);
            const errorMessage =
                error.response?.data?.message || "Error al eliminar lugar";
            showMessage(errorMessage, "error");
        } finally {
            setPlaceDeleting(false);
        }
    };

    const showMessage = (msg, type = "success") => {
        setMessage(msg);
        setMessageType(type);
        setTimeout(() => setMessage(""), 3000);
    };

    const handleAccept = async (reservation) => {
        try {
            setLoading(true);
            await companyService.reservations.accept(reservation.id);
            showMessage("Reserva aceptada correctamente", "success");
            loadReservations();
            loadStats();
        } catch (error) {
            showMessage("Error al aceptar reserva", "error");
        } finally {
            setLoading(false);
        }
    };

    const handleRejectSubmit = async (formData) => {
        try {
            setRejecting(true);
            await companyService.reservations.reject(
                selectedReservation.id,
                formData,
            );
            showMessage("Reserva rechazada correctamente", "success");
            setShowRejectModal(false);
            setSelectedReservation(null);
            loadReservations();
            loadStats();
        } catch (error) {
            showMessage("Error al rechazar reserva", "error");
        } finally {
            setRejecting(false);
        }
    };

    const handleReopen = async (reservation) => {
        try {
            setLoading(true);
            await companyService.reservations.reopen(reservation.id);
            showMessage("Reserva reabierta correctamente", "success");
            loadReservations();
            loadStats();
        } catch (error) {
            showMessage("Error al reabrir reserva", "error");
        } finally {
            setLoading(false);
        }
    };

    const handleViewDetails = (reservation) => {
        setSelectedReservation(reservation);
    };

    const formatDate = (date) => {
        const dateObj = parseLocalDate(date);
        if (!dateObj) {
            return "Fecha invalida";
        }

        return dateObj.toLocaleDateString("es-CO", {
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    };

    const parseLocalDate = (dateString) => {
        if (!dateString) return null;

        let dateStr = String(dateString);
        if (dateStr.includes("T")) {
            dateStr = dateStr.split("T")[0];
        } else if (dateStr.includes(" ")) {
            dateStr = dateStr.split(" ")[0];
        }

        const [year, month, day] = dateStr.split("-").map(Number);
        if (!year || !month || !day) {
            const fallback = new Date(dateString);
            return isNaN(fallback.getTime()) ? null : fallback;
        }

        return new Date(year, month - 1, day);
    };

    const formatTime = (time) => {
        return time.substring(0, 5);
    };

    if (loading && reservations.length === 0) {
        return <div className="company-dashboard loading">Cargando...</div>;
    }

    return (
        <div className="company-dashboard">
            <div className="dashboard-header">
                <h1>Panel de Empresa</h1>
                <p>Gestiona reservas, tu lugar y horarios</p>
                <button
                    type="button"
                    className="back-button"
                    onClick={() => window.history.back()}
                >
                    Volver al inicio
                </button>
            </div>

            {message && (
                <div className={`alert alert-${messageType}`}>{message}</div>
            )}

            {/* Stats Cards */}
            <div className="stats-container">
                <div
                    className={`stat-card ${filter === "pendiente" ? "active" : ""}`}
                    onClick={() => setFilter("pendiente")}
                >
                    <div className="stat-value">{stats.pendientes}</div>
                    <div className="stat-label">Pendientes</div>
                </div>
                <div
                    className={`stat-card ${filter === "aceptada" ? "active" : ""}`}
                    onClick={() => setFilter("aceptada")}
                >
                    <div className="stat-value">{stats.aceptadas}</div>
                    <div className="stat-label">Aceptadas</div>
                </div>
                <div
                    className={`stat-card ${filter === "rechazada" ? "active" : ""}`}
                    onClick={() => setFilter("rechazada")}
                >
                    <div className="stat-value">{stats.rechazadas}</div>
                    <div className="stat-label">Rechazadas</div>
                </div>
                <div className="stat-card total">
                    <div className="stat-value">{stats.total}</div>
                    <div className="stat-label">Total</div>
                </div>
            </div>

            {/* Filter Tabs */}
            <div className="filter-tabs">
                <button
                    className={`tab ${filter === "pendiente" ? "active" : ""}`}
                    onClick={() => setFilter("pendiente")}
                >
                    Pendientes
                </button>
                <button
                    className={`tab ${filter === "aceptada" ? "active" : ""}`}
                    onClick={() => setFilter("aceptada")}
                >
                    Aceptadas
                </button>
                <button
                    className={`tab ${filter === "rechazada" ? "active" : ""}`}
                    onClick={() => setFilter("rechazada")}
                >
                    Rechazadas
                </button>
            </div>

            <div className="schedule-manager">
                <div className="schedule-header">
                    <h2>Horarios del lugar</h2>
                    <div className="schedule-controls">
                        <label>
                            Lugar
                            <select
                                value={selectedPlaceId}
                                onChange={(event) =>
                                    setSelectedPlaceId(event.target.value)
                                }
                                disabled={scheduleLoading}
                            >
                                {placesManaged.length === 0 && (
                                    <option value="">Sin lugares</option>
                                )}
                                {placesManaged.map((place) => (
                                    <option key={place.id} value={place.id}>
                                        {place.name}
                                    </option>
                                ))}
                            </select>
                        </label>
                    </div>
                </div>

                {scheduleLoading ? (
                    <p>Cargando horarios...</p>
                ) : schedules.length === 0 ? (
                    <p>No hay horarios configurados para este lugar.</p>
                ) : (
                    <div className="schedule-list">
                        {schedules.map((schedule) => (
                            <div
                                key={schedule.id}
                                className={`schedule-row ${schedule.activo ? "" : "inactive"}`}
                            >
                                <select
                                    value={schedule.dia_semana}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "dia_semana",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                >
                                    <option value="lunes">Lunes</option>
                                    <option value="martes">Martes</option>
                                    <option value="miercoles">Miercoles</option>
                                    <option value="jueves">Jueves</option>
                                    <option value="viernes">Viernes</option>
                                    <option value="sabado">Sabado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                                <input
                                    type="time"
                                    value={schedule.hora_inicio}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "hora_inicio",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                />
                                <input
                                    type="time"
                                    value={schedule.hora_fin}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "hora_fin",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                />
                                <label className="schedule-active">
                                    <input
                                        type="checkbox"
                                        checked={!!schedule.activo}
                                        onChange={(event) =>
                                            handleScheduleChange(
                                                schedule.id,
                                                "activo",
                                                event.target.checked,
                                            )
                                        }
                                        disabled={scheduleLoading}
                                    />
                                    Activo
                                </label>
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={() => handleAllDay(schedule.id)}
                                    disabled={scheduleLoading}
                                >
                                    Todo el dia
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-success"
                                    onClick={() => handleSaveSchedule(schedule)}
                                    disabled={
                                        scheduleLoading || !schedule._dirty
                                    }
                                >
                                    Guardar
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    onClick={() =>
                                        handleDeleteSchedule(schedule.id)
                                    }
                                    disabled={scheduleLoading}
                                >
                                    Eliminar
                                </button>
                            </div>
                        ))}
                    </div>
                )}

                <div className="schedule-new">
                    <h3>Agregar horario</h3>
                    <div className="schedule-row">
                        <select
                            value={newSchedule.dia_semana}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    dia_semana: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        >
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miercoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sabado">Sabado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                        <input
                            type="time"
                            value={newSchedule.hora_inicio}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_inicio: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        />
                        <input
                            type="time"
                            value={newSchedule.hora_fin}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_fin: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        />
                        <label className="schedule-active">
                            <input
                                type="checkbox"
                                checked={!!newSchedule.activo}
                                onChange={(event) =>
                                    setNewSchedule({
                                        ...newSchedule,
                                        activo: event.target.checked,
                                    })
                                }
                                disabled={scheduleLoading}
                            />
                            Activo
                        </label>
                        <button
                            type="button"
                            className="btn btn-secondary"
                            onClick={() =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_inicio: "00:00",
                                    hora_fin: "23:59",
                                })
                            }
                            disabled={scheduleLoading}
                        >
                            Todo el dia
                        </button>
                        <button
                            type="button"
                            className="btn btn-success"
                            onClick={handleAddSchedule}
                            disabled={scheduleLoading}
                        >
                            Agregar
                        </button>
                    </div>
                    <p className="schedule-hint">
                        Para cerrar un dia, desactiva el horario o eliminelo.
                        Para abrir todo el dia usa 00:00 - 23:59.
                    </p>
                </div>
            </div>

            {/* Reservations List */}
            <div className="reservations-list">
                {reservations.length === 0 ? (
                    <div className="empty-state">
                        <p>No hay reservas en esta categoría</p>
                    </div>
                ) : (
                    reservations.map((companyRes) => {
                        const reservation = companyRes.reservation;
                        const peopleCount = getPeopleCount(reservation);
                        return (
                            <div
                                key={companyRes.id}
                                className="reservation-card"
                            >
                                <div className="card-header">
                                    <h3>{reservation.place?.name}</h3>
                                    <span
                                        className={`status-badge status-${companyRes.estado}`}
                                    >
                                        {companyRes.estado
                                            .charAt(0)
                                            .toUpperCase() +
                                            companyRes.estado.slice(1)}
                                    </span>
                                </div>

                                <div className="card-body">
                                    <div className="reservation-info">
                                        <div className="info-row">
                                            <span className="label">
                                                Visitante:
                                            </span>
                                            <span className="value">
                                                {reservation.usuario?.name ||
                                                    "N/A"}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="label">
                                                Email:
                                            </span>
                                            <span className="value">
                                                {reservation.usuario?.email ||
                                                    "N/A"}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="label">
                                                Fecha:
                                            </span>
                                            <span className="value">
                                                {formatDate(
                                                    reservation.fecha_visita,
                                                )}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="label">Hora:</span>
                                            <span className="value">
                                                {formatTime(
                                                    reservation.hora_visita,
                                                )}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="label">
                                                Personas:
                                            </span>
                                            <span className="value">
                                                {peopleCount ?? "N/A"}
                                            </span>
                                        </div>
                                    </div>

                                    <div className="reservation-actions">
                                        {companyRes.estado === "pendiente" && (
                                            <>
                                                <button
                                                    className="btn btn-success"
                                                    onClick={() =>
                                                        handleAccept(companyRes)
                                                    }
                                                    disabled={loading}
                                                >
                                                    Aceptar
                                                </button>
                                                <button
                                                    className="btn btn-danger"
                                                    onClick={() => {
                                                        setSelectedReservation(
                                                            companyRes,
                                                        );
                                                        setShowRejectModal(
                                                            true,
                                                        );
                                                    }}
                                                    disabled={loading}
                                                >
                                                    Rechazar
                                                </button>
                                            </>
                                        )}

                                        {companyRes.estado === "rechazada" && (
                                            <button
                                                className="btn btn-secondary"
                                                onClick={() =>
                                                    handleReopen(companyRes)
                                                }
                                                disabled={loading}
                                            >
                                                Reabrir
                                            </button>
                                        )}

                                        {companyRes.estado === "aceptada" && (
                                            <button
                                                className="btn btn-secondary"
                                                onClick={() =>
                                                    handleReopen(companyRes)
                                                }
                                                disabled={loading}
                                            >
                                                Revertir
                                            </button>
                                        )}

                                        <button
                                            className="btn btn-outline"
                                            onClick={() =>
                                                handleViewDetails(companyRes)
                                            }
                                        >
                                            Ver Detalles
                                        </button>
                                    </div>
                                </div>

                                {selectedReservation &&
                                    selectedReservation.id ===
                                    companyRes.id && (
                                        <div className="card-footer">
                                            <h4>Detalles de la Reserva</h4>
                                            <p>
                                                <strong>
                                                    Número de personas:
                                                </strong>{" "}
                                                {peopleCount ?? "N/A"}
                                            </p>
                                            <p>
                                                <strong>Comentarios:</strong>{" "}
                                                {reservation.comentarios ||
                                                    "Sin comentarios"}
                                            </p>
                                            {companyRes.estado ===
                                                "rechazada" && (
                                                    <>
                                                        <p>
                                                            <strong>
                                                                Motivo de rechazo:
                                                            </strong>{" "}
                                                            {companyRes
                                                                .rejectionReason
                                                                ?.descripcion ||
                                                                "N/A"}
                                                        </p>
                                                        {companyRes.comentario_rechazo && (
                                                            <p>
                                                                <strong>
                                                                    Comentario:
                                                                </strong>{" "}
                                                                {
                                                                    companyRes.comentario_rechazo
                                                                }
                                                            </p>
                                                        )}
                                                    </>
                                                )}
                                        </div>
                                    )}
                            </div>
                        );
                    })
                )}
            </div>

            <div className="place-manager">
                <div className="place-header">
                    <h2>Mi lugar</h2>
                    <div className="place-controls">
                        <label>
                            Lugar
                            <select
                                value={selectedPlaceId}
                                onChange={(event) =>
                                    setSelectedPlaceId(event.target.value)
                                }
                                disabled={
                                    placeLoading || placeSaving || placeDeleting
                                }
                            >
                                {placesManaged.length === 0 && (
                                    <option value="">Sin lugares</option>
                                )}
                                {placesManaged.map((place) => (
                                    <option key={place.id} value={place.id}>
                                        {place.name}
                                    </option>
                                ))}
                            </select>
                        </label>
                    </div>
                </div>

                {placesManaged.length === 0 ? (
                    <p>No tienes lugares asignados.</p>
                ) : placeLoading ? (
                    <p>Cargando lugar...</p>
                ) : (
                    <div className="place-form">
                        <div className="place-form-grid">
                            <label>
                                Nombre
                                <input
                                    type="text"
                                    value={placeForm.name}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "name",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label>
                                Ubicación
                                <input
                                    type="text"
                                    value={placeForm.location}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "location",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label className="place-form-full">
                                Descripción
                                <textarea
                                    value={placeForm.description}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "description",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                    rows={4}
                                />
                            </label>
                            <label>
                                Latitud
                                <input
                                    type="number"
                                    step="0.000001"
                                    value={placeForm.latitude}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "latitude",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label>
                                Longitud
                                <input
                                    type="number"
                                    step="0.000001"
                                    value={placeForm.longitude}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "longitude",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label>
                                Teléfono
                                <input
                                    type="text"
                                    value={placeForm.telefono}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "telefono",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label>
                                Email
                                <input
                                    type="email"
                                    value={placeForm.email}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "email",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label>
                                Sitio web
                                <input
                                    type="url"
                                    value={placeForm.sitio_web}
                                    onChange={(event) =>
                                        handlePlaceChange(
                                            "sitio_web",
                                            event.target.value,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                            <label className="place-form-full">
                                Imagen
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={(event) =>
                                        handlePlaceImageChange(
                                            event.target.files?.[0] || null,
                                        )
                                    }
                                    disabled={placeSaving || placeDeleting}
                                />
                            </label>
                        </div>

                        {(placeForm.image || placeImagePreview) && (
                            <div className="place-image-preview">
                                <img
                                    src={localImagePreview || placeImagePreview}
                                    alt="Imagen del lugar"
                                />
                            </div>
                        )}

                        <div className="place-actions">
                            <button
                                type="button"
                                className="btn btn-success"
                                onClick={handlePlaceSave}
                                disabled={placeSaving || placeDeleting}
                            >
                                {placeSaving
                                    ? "Guardando..."
                                    : "Guardar cambios"}
                            </button>
                            <button
                                type="button"
                                className="btn btn-danger"
                                onClick={handlePlaceDelete}
                                disabled={placeSaving || placeDeleting}
                            >
                                {placeDeleting
                                    ? "Eliminando..."
                                    : "Eliminar lugar"}
                            </button>
                        </div>
                    </div>
                )}
            </div>

            <div className="schedule-manager">
                <div className="schedule-header">
                    <h2>Horarios del lugar</h2>
                    <div className="schedule-controls">
                        <label>
                            Lugar
                            <select
                                value={selectedPlaceId}
                                onChange={(event) =>
                                    setSelectedPlaceId(event.target.value)
                                }
                                disabled={scheduleLoading}
                            >
                                {placesManaged.length === 0 && (
                                    <option value="">Sin lugares</option>
                                )}
                                {placesManaged.map((place) => (
                                    <option key={place.id} value={place.id}>
                                        {place.name}
                                    </option>
                                ))}
                            </select>
                        </label>
                    </div>
                </div>

                {scheduleLoading ? (
                    <p>Cargando horarios...</p>
                ) : schedules.length === 0 ? (
                    <p>No hay horarios configurados para este lugar.</p>
                ) : (
                    <div className="schedule-list">
                        {schedules.map((schedule) => (
                            <div
                                key={schedule.id}
                                className={`schedule-row ${schedule.activo ? "" : "inactive"}`}
                            >
                                <select
                                    value={schedule.dia_semana}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "dia_semana",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                >
                                    <option value="lunes">Lunes</option>
                                    <option value="martes">Martes</option>
                                    <option value="miercoles">Miercoles</option>
                                    <option value="jueves">Jueves</option>
                                    <option value="viernes">Viernes</option>
                                    <option value="sabado">Sabado</option>
                                    <option value="domingo">Domingo</option>
                                </select>
                                <input
                                    type="time"
                                    value={schedule.hora_inicio}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "hora_inicio",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                />
                                <input
                                    type="time"
                                    value={schedule.hora_fin}
                                    onChange={(event) =>
                                        handleScheduleChange(
                                            schedule.id,
                                            "hora_fin",
                                            event.target.value,
                                        )
                                    }
                                    disabled={scheduleLoading}
                                />
                                <label className="schedule-active">
                                    <input
                                        type="checkbox"
                                        checked={!!schedule.activo}
                                        onChange={(event) =>
                                            handleScheduleChange(
                                                schedule.id,
                                                "activo",
                                                event.target.checked,
                                            )
                                        }
                                        disabled={scheduleLoading}
                                    />
                                    Activo
                                </label>
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={() => handleAllDay(schedule.id)}
                                    disabled={scheduleLoading}
                                >
                                    Todo el dia
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-success"
                                    onClick={() => handleSaveSchedule(schedule)}
                                    disabled={
                                        scheduleLoading || !schedule._dirty
                                    }
                                >
                                    Guardar
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-danger"
                                    onClick={() =>
                                        handleDeleteSchedule(schedule.id)
                                    }
                                    disabled={scheduleLoading}
                                >
                                    Eliminar
                                </button>
                            </div>
                        ))}
                    </div>
                )}

                <div className="schedule-new">
                    <h3>Agregar horario</h3>
                    <div className="schedule-row">
                        <select
                            value={newSchedule.dia_semana}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    dia_semana: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        >
                            <option value="lunes">Lunes</option>
                            <option value="martes">Martes</option>
                            <option value="miercoles">Miercoles</option>
                            <option value="jueves">Jueves</option>
                            <option value="viernes">Viernes</option>
                            <option value="sabado">Sabado</option>
                            <option value="domingo">Domingo</option>
                        </select>
                        <input
                            type="time"
                            value={newSchedule.hora_inicio}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_inicio: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        />
                        <input
                            type="time"
                            value={newSchedule.hora_fin}
                            onChange={(event) =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_fin: event.target.value,
                                })
                            }
                            disabled={scheduleLoading}
                        />
                        <label className="schedule-active">
                            <input
                                type="checkbox"
                                checked={!!newSchedule.activo}
                                onChange={(event) =>
                                    setNewSchedule({
                                        ...newSchedule,
                                        activo: event.target.checked,
                                    })
                                }
                                disabled={scheduleLoading}
                            />
                            Activo
                        </label>
                        <button
                            type="button"
                            className="btn btn-secondary"
                            onClick={() =>
                                setNewSchedule({
                                    ...newSchedule,
                                    hora_inicio: "00:00",
                                    hora_fin: "23:59",
                                })
                            }
                            disabled={scheduleLoading}
                        >
                            Todo el dia
                        </button>
                        <button
                            type="button"
                            className="btn btn-success"
                            onClick={handleAddSchedule}
                            disabled={scheduleLoading}
                        >
                            Agregar
                        </button>
                    </div>
                    <p className="schedule-hint">
                        Para cerrar un dia, desactiva el horario o eliminelo.
                        Para abrir todo el dia usa 00:00 - 23:59.
                    </p>
                </div>
            </div>

            {/* Reject Modal */}
            <RejectReservationModal
                isOpen={showRejectModal}
                onClose={() => setShowRejectModal(false)}
                onReject={handleRejectSubmit}
                loading={rejecting}
                rejectionReasons={rejectionReasons}
            />
        </div>
    );
};

export default CompanyDashboard;
