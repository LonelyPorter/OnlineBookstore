package com.example.bookstore;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;


public class PublisherActivity extends AppCompatActivity {
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_publisher);

        // publisher textview
        TextView publisherName = (TextView) findViewById(R.id.textView_publisherName);
        TextView PublisherAddr = (TextView) findViewById(R.id.textView_publisherAddr);

        // get information from other activity
        Intent intent = getIntent();

        // get publisher name, address and books
        String pname = intent.getStringExtra("name");
        String paddr = intent.getStringExtra("address");
        String[] ptitle = intent.getStringArrayExtra("titles");

        // set publisher textview info
        String resultName = "Publisher Name:  " + pname;
        String resultAddr = "Publisher Address:  " + paddr;

        // display publisher name and address
        publisherName.setText(resultName);
        PublisherAddr.setText(resultAddr);

        // set publisher books table
        Context context = PublisherActivity.this;
        TableLayout publisherTable = (TableLayout) findViewById(R.id.publisherResultTable);

        // get book array length
        int rows = ptitle.length;

        // parse books data
        for (int i = 0; i < rows; i++) {
            // set books table
            TableRow row = new TableRow(context);
            TableRow.LayoutParams layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
            row.setLayoutParams(layoutParams);

            // set row
            TextView title = new TextView(context);
            TableRow.LayoutParams l1 = new TableRow.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT, 1);

            // set books title
            title.setText(ptitle[i]);
            title.setLayoutParams(l1);
            title.setGravity(Gravity.CENTER);
            title.setBackground(getDrawable(R.drawable.border));

            // add data to row
            row.addView(title);

            // add row to table
            publisherTable.addView(row, i + 1);
        }
    }
}
