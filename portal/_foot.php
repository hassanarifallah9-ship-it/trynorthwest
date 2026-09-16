		</div>
	</div>
</div>
<script>
(function(){
	var side = document.getElementById('sideNav');
	var btn = document.getElementById('menuToggle');
	var backdrop = document.getElementById('navBackdrop');
	function close(){ side.classList.remove('open'); backdrop.classList.remove('show'); }
	function toggle(){ side.classList.toggle('open'); backdrop.classList.toggle('show'); }
	if (btn) btn.addEventListener('click', toggle);
	if (backdrop) backdrop.addEventListener('click', close);
	document.querySelectorAll('table.data').forEach(function(t){
		if (t.parentElement && t.parentElement.classList.contains('table-scroll')) return;
		var w = document.createElement('div');
		w.className = 'table-scroll';
		t.parentNode.insertBefore(w, t);
		w.appendChild(t);
	});
})();
</script>
</body>
</html>
