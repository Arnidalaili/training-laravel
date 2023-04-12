<style type="text/css">
	input, select, textarea {
		text-transform: UPPERCASE;
		padding: 5px;
	}

</style>
<form id="penjualanaddForm">
    <table width="100%" cellspacing="0" id="addData">
        <tr>
            <td>
                <label>No.Invoice</label>
            </td>
            <td>
                <input type="text" id="Invoice" name="Invoice" class="FormElement ui-widget-content ui-corner-all" readonly onkeyup="this.value=this.value.toUpperCase();" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td>
                <label>Nama Customer</label>
            </td>
            <td>
                <input type="text" id="Nama" name="Nama" class="FormElement ui-widget-content ui-corner-all" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
            </td>
        </tr>
        <tr>
            <td>
                <label>Tanggal Pembelian</label>
            </td>
            <td>
                <input type="text" id="Tanggal" name="Tanggal" class="FormElement ui-widget-content ui-corner-all setDate" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off" maxlength="10">
            </td>
        </tr>
        <tr>
            <td>
                <label>Jenis Kelamin</label>
            </td>
            <td>
                <select id="Jeniskelamin" class="FormElement ui-widget-content ui-corner-all JenisKelamin" name="Jeniskelamin" onkeyup="this.value=this.value.toUpperCase();" required>
                    <option value= 'LAKI-LAKI'>LAKI-LAKI</option>
                    <option value= 'PEREMPUAN'>PEREMPUAN</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label>Saldo</label>
            </td>
            <td>
                <input type="text" id="Saldo" name="Saldo" class="FormElement ui-widget-content ui-corner-all im-currency" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" class="table ui-state-default" cellpading="5" cellspacing="0" id="detailData">
        <thead>
			<tr>
				<th class="ui-th-div">Nama Barang</th>
				<th class="ui-th-div">Qty</th>
				<th class="ui-th-div">Harga</th>
				<th class="ui-th-div">Action</th>
			</tr>
		</thead>
        <tbody>
            <tr>
                <td>
                    <input type="text" name="NamaBarang[]" id="namabarang" class="FormElement ui-widget-content ui-corner-all" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
                </td>
                <td>
                    <input type="text" name="Qty[]" id="qty" class="FormElement ui-widget-content ui-corner-all im-numeric" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
                </td>
                <td>
                    <input type="text" name="Harga[]" id="harga" class="FormElement ui-widget-content ui-corner-all im-currency" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
                </td>
                <td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
            </tr>
            <tr>
				<td colspan="3"></td>
				<td>
					<a href="javascript:" onclick="addRow(); setNumericFormat(); setCustomBindKeys();">
						<span class="ui-icon ui-icon-plus"></span>
					</a>
				</td>
			</tr>
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function() {
		let index = 0

		setDateFormat()
		setNumericFormat()
        setSelect2()
        setCustomBindKeys()
	})


    function addRow()
    {
        $('#detailData tbody tr').last().before(`
            <tr>
                <td>
                    <input type="text" name="NamaBarang[]" id="namabarang" class="FormElement ui-widget-content ui-corner-all" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
                </td>
                <td>
					<input type="text" name="Qty[]" id="qty" class="FormElement ui-widget-content ui-corner-all im-numeric" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
				</td>
				<td>
					<input type="text" name="Harga[]" id="harga" class="FormElement ui-widget-content ui-corner-all im-currency" onkeyup="this.value=this.value.toUpperCase();" required autocomplete="off">
				</td>
                <td>
					<a href="javascript:">
						<span class="ui-icon ui-icon-trash" onclick="$(this).parent().parent().parent().remove()"></span>
					</a>
				</td>
            </tr>
        `)
    }

    function setDateFormat() 
    {
        $('.setDate').datepicker({
			dateFormat: 'dd-mm-yy',
		}).inputmask({
			alias: "datetime",
            mask: "1-2-y",
            separator: "-"
		})
    }

    function setSelect2()
    {
        $('.JenisKelamin').select2()
    }

    function setNumericFormat() {
        $('.im-numeric').keypress(function(e){
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
            {
                return false;
            }
        })

        $('.im-currency').inputmask('integer', {
            alias: 'numeric',
            groupSeparator: '.',
            autoGroup: true,
			digitsOptional: false,
			allowMinus: false,
			placeholder: '',
        })
    }


    function setCustomBindKeys(grid) {
        $(document).on("keydown", function (e) {
            if (activeGrid) {
                if (
                    e.keyCode == 33 ||
                    e.keyCode == 34 ||
                    e.keyCode == 35 ||
                    e.keyCode == 36 ||
                    e.keyCode == 38 ||
                    e.keyCode == 40 ||
                    e.keyCode == 13
                ) {
                    e.preventDefault();

                    var gridIds = $(activeGrid).getDataIDs();
                    var selectedRow = $(activeGrid).getGridParam("selrow");
                    var currentPage = $(activeGrid).getGridParam("page");
                    var lastPage = $(activeGrid).getGridParam("lastpage");
                    var currentIndex = 0;
                    var row = $(activeGrid).jqGrid("getGridParam", "postData").rows;

                    for (var i = 0; i < gridIds.length; i++) {
                        if (gridIds[i] == selectedRow) currentIndex = i;
                    }

                    if (triggerClick == false) {
                        if (33 === e.keyCode) {
                            if (currentPage > 1) {
                                $(activeGrid)
                                    .jqGrid("setGridParam", {
                                        page: parseInt(currentPage) - 1,
                                    })
                                    .trigger("reloadGrid");

                                triggerClick = true;
                            }
                            $(activeGrid).triggerHandler("jqGridKeyUp"), e.preventDefault();
                        }
                        if (34 === e.keyCode) {
                            if (currentPage !== lastPage) {
                                $(activeGrid)
                                    .jqGrid("setGridParam", {
                                        page: parseInt(currentPage) + 1,
                                    })
                                    .trigger("reloadGrid");

                                triggerClick = true;
                            }
                            $(activeGrid).triggerHandler("jqGridKeyUp"), e.preventDefault();
                        }
                        if (35 === e.keyCode) {
                            if (currentPage !== lastPage) {
                                $(activeGrid)
                                    .jqGrid("setGridParam", {
                                        page: lastPage,
                                    })
                                    .trigger("reloadGrid");
                                if (e.ctrlKey) {
                                    if (
                                        $(activeGrid).jqGrid("getGridParam", "selrow") !==
                                        $("#customer")
                                            .find(">tbody>tr.jqgrow")
                                            .filter(":last")
                                            .attr("id")
                                    ) {
                                        $(activeGrid)
                                            .jqGrid(
                                                "setSelection",
                                                $(activeGrid)
                                                    .find(">tbody>tr.jqgrow")
                                                    .filter(":last")
                                                    .attr("id")
                                            )
                                            .trigger("reloadGrid");
                                    }
                                }

                                triggerClick = true;
                            }
                            if (e.ctrlKey) {
                                if (
                                    $(activeGrid).jqGrid("getGridParam", "selrow") !==
                                    $("#customer")
                                        .find(">tbody>tr.jqgrow")
                                        .filter(":last")
                                        .attr("id")
                                ) {
                                    $(activeGrid)
                                        .jqGrid(
                                            "setSelection",
                                            $(activeGrid)
                                                .find(">tbody>tr.jqgrow")
                                                .filter(":last")
                                                .attr("id")
                                        )
                                        .trigger("reloadGrid");
                                }
                            }
                            $(activeGrid).triggerHandler("jqGridKeyUp"), e.preventDefault();
                        }
                        if (36 === e.keyCode) {
                            if (currentPage > 1) {
                                if (e.ctrlKey) {
                                    if (
                                        $(activeGrid).jqGrid("getGridParam", "selrow") !==
                                        $("#customer")
                                            .find(">tbody>tr.jqgrow")
                                            .filter(":first")
                                            .attr("id")
                                    ) {
                                        $(activeGrid).jqGrid(
                                            "setSelection",
                                            $(activeGrid)
                                                .find(">tbody>tr.jqgrow")
                                                .filter(":first")
                                                .attr("id")
                                        );
                                    }
                                }
                                $(activeGrid)
                                    .jqGrid("setGridParam", {
                                        page: 1,
                                    })
                                    .trigger("reloadGrid");

                                triggerClick = true;
                            }
                            $(activeGrid).triggerHandler("jqGridKeyUp"), e.preventDefault();
                        }
                        if (38 === e.keyCode) {
                            if (currentIndex - 1 >= 0) {
                                $(activeGrid)
                                    .resetSelection()
                                    .setSelection(gridIds[currentIndex - 1]);
                            }
                        }
                        if (40 === e.keyCode) {
                            if (currentIndex + 1 < gridIds.length) {
                                $(activeGrid)
                                    .resetSelection()
                                    .setSelection(gridIds[currentIndex + 1]);
                            }
                        }
                        if (13 === e.keyCode) {
                            let rowId = $(activeGrid).getGridParam("selrow");
                            let ondblClickRowHandler = $(activeGrid).jqGrid(
                                "getGridParam",
                                "ondblClickRow"
                            );

                            if (ondblClickRowHandler) {
                                ondblClickRowHandler.call($(activeGrid)[0], rowId);
                            }
                        }
                    }
                }
            }
        });
    }
</script>